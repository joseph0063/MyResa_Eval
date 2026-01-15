<?php

declare(strict_types=1);

/**
 * Form Validation Helper
 *
 * Fluent validation class for form data with chainable validation rules.
 *
 * @package MiniMovies\Helpers
 * @requires PHP 8.1
 */
class Validator
{
    /** @var array<string, string> Field-specific error messages */
    private array $errors = [];

    /** @var array<string, mixed> Data being validated */
    private array $data;

    /**
     * @param array<string, mixed> $data Data to validate (typically $_POST)
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Validate field is present and not empty.
     */
    public function required(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';

        if (is_string($value) && trim($value) === '') {
            $this->addError($field, $message ?? "The {$field} field is required.");
        }

        return $this;
    }

    /**
     * Validate field is a valid email format.
     */
    public function email(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, $message ?? "The {$field} must be a valid email address.");
        }

        return $this;
    }

    /**
     * Validate field has minimum length.
     */
    public function min(string $field, int $length, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '' && strlen($value) < $length) {
            $this->addError($field, $message ?? "The {$field} must be at least {$length} characters.");
        }

        return $this;
    }

    /**
     * Validate field has maximum length.
     */
    public function max(string $field, int $length, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '' && strlen($value) > $length) {
            $this->addError($field, $message ?? "The {$field} must not exceed {$length} characters.");
        }

        return $this;
    }

    /**
     * Validate field matches its confirmation field ({field}_confirmation).
     */
    public function confirmed(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';
        $confirmation = $this->data[$field . '_confirmation'] ?? '';

        if ($value !== $confirmation) {
            $this->addError($field, $message ?? "The {$field} confirmation does not match.");
        }

        return $this;
    }

    /**
     * Validate field value is unique in database table.
     */
    public function unique(string $field, string $table, string $column, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '') {
            $sql = "SELECT COUNT(*) FROM {$table} WHERE {$column} = :value";
            $stmt = db()->prepare($sql);
            $stmt->execute(['value' => $value]);

            if ((int) $stmt->fetchColumn() > 0) {
                $this->addError($field, $message ?? "The {$field} has already been taken.");
            }
        }

        return $this;
    }

    /**
     * Validate field is numeric.
     */
    public function numeric(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '' && !is_numeric($value)) {
            $this->addError($field, $message ?? "The {$field} must be a number.");
        }

        return $this;
    }

    /**
     * Validate field is an integer.
     */
    public function integer(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_INT) && $value !== '0') {
            $this->addError($field, $message ?? "The {$field} must be an integer.");
        }

        return $this;
    }

    /**
     * Validate field is a decimal number with optional precision.
     *
     * @param int|null $decimals Max decimal places allowed (null = any)
     */
    public function decimal(string $field, ?int $decimals = null, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '') {
            if (!is_numeric($value)) {
                $this->addError($field, $message ?? "The {$field} must be a decimal number.");
                return $this;
            }

            if ($decimals !== null) {
                $parts = explode('.', (string) $value);
                if (isset($parts[1]) && strlen($parts[1]) > $decimals) {
                    $this->addError($field, $message ?? "The {$field} must have at most {$decimals} decimal places.");
                }
            }
        }

        return $this;
    }

    /**
     * Validate field is between min and max values.
     */
    public function between(string $field, float $min, float $max, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '' && is_numeric($value)) {
            $numValue = (float) $value;
            if ($numValue < $min || $numValue > $max) {
                $this->addError($field, $message ?? "The {$field} must be between {$min} and {$max}.");
            }
        }

        return $this;
    }

    /**
     * Validate field is a valid URL.
     */
    public function url(string $field, ?string $message = null): self
    {
        $value = $this->data[$field] ?? '';

        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_URL)) {
            $this->addError($field, $message ?? "The {$field} must be a valid URL.");
        }

        return $this;
    }

    /**
     * Check if validation failed.
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get all validation errors.
     *
     * @return array<string, string>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Get only the validated fields (fields that were checked).
     *
     * @return array<string, mixed>
     */
    public function validated(): array
    {
        $validated = [];

        foreach (array_keys($this->errors) as $field) {
            if (isset($this->data[$field])) {
                $validated[$field] = $this->data[$field];
            }
        }

        foreach ($this->data as $field => $value) {
            if (!isset($this->errors[$field])) {
                $validated[$field] = $value;
            }
        }

        return $validated;
    }

    /**
     * Add an error for a field (only first error per field is kept).
     */
    private function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }
}
