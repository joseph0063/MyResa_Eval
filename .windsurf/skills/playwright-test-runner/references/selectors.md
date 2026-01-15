# Selectors Reference

This file contains reusable selector patterns for Playwright tests.

## Common Patterns

### Buttons
```javascript
page.getByRole('button', { name: 'Save' })
page.getByRole('button', { name: 'Cancel' })
page.getByRole('button', { name: 'Submit' })
```

### Form Fields
```javascript
page.getByRole('textbox', { name: 'Email' })
page.getByRole('textbox', { name: 'Password' })
page.getByRole('combobox', { name: 'Select...' })
```

### Links
```javascript
page.getByRole('link', { name: 'Home' })
page.getByRole('link', { name: 'Login' })
```

### Tables
```javascript
page.locator('table tbody tr')
page.locator('table tbody tr').first()
page.locator('table tbody tr td').nth(0)
```

## nth() Pattern

When multiple similar elements exist:
```javascript
// First matching element
page.getByRole('combobox').nth(0)

// Second matching element  
page.getByRole('combobox').nth(1)
```

## Waiting Patterns

```javascript
// Wait for element visible
await page.waitForSelector('.element', { state: 'visible' });

// Wait for element hidden
await page.waitForSelector('.loading', { state: 'hidden' });

// Wait for text
await page.waitForSelector('text=Success');
```
