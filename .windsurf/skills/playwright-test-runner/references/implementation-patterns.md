# Implementation Patterns

Common patterns for implementing Playwright tests.

## Test Structure

```typescript
import { test, expect } from '@playwright/test';

test.describe('Feature Name', () => {
  test.beforeEach(async ({ page }) => {
    // Setup before each test
    await page.goto('/login');
  });

  test('TEST-1.1: Description', async ({ page }) => {
    // Test implementation
  });
});
```

## Authentication Fixture

```typescript
// fixtures/auth.ts
import { test as base } from '@playwright/test';

export const test = base.extend({
  authenticatedPage: async ({ page }, use) => {
    await page.goto('/login');
    await page.fill('[name="email"]', 'user@example.com');
    await page.fill('[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');
    await use(page);
  }
});
```

## Wait Patterns

```typescript
// Wait for loading to complete
await page.waitForSelector('.loading', { state: 'hidden', timeout: 15000 });

// Wait for AJAX
await page.waitForResponse(response => 
  response.url().includes('/api/data') && response.status() === 200
);

// Wait for navigation
await page.waitForURL('/success');
```

## Form Filling

```typescript
// Fill form fields
await page.fill('[name="email"]', 'test@example.com');
await page.fill('[name="password"]', 'password123');

// Select dropdown
await page.selectOption('select[name="country"]', 'US');

// Check checkbox
await page.check('[name="terms"]');

// Click submit
await page.click('button[type="submit"]');
```

## Assertions

```typescript
// Text content
await expect(page.locator('.message')).toContainText('Success');

// Visibility
await expect(page.locator('.modal')).toBeVisible();
await expect(page.locator('.loading')).toBeHidden();

// Count
await expect(page.locator('table tr')).toHaveCount(5);

// Value
await expect(page.locator('input[name="email"]')).toHaveValue('test@example.com');
```

## Error Handling

```typescript
test('handles errors gracefully', async ({ page }) => {
  // Submit invalid form
  await page.click('button[type="submit"]');
  
  // Verify error message
  await expect(page.locator('.error')).toBeVisible();
  await expect(page.locator('.error')).toContainText('Required field');
});
```
