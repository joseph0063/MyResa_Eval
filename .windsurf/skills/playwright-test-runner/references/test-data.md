# Test Data Reference

This file contains test data configurations for Playwright tests.

## Structure

Define test data in a config file:

```typescript
// config.ts
export const TEST_DATA = {
  users: {
    admin: {
      email: 'admin@example.com',
      password: 'password123'
    },
    user: {
      email: 'user@example.com', 
      password: 'password123'
    }
  },
  items: {
    item1: { id: 1, name: 'Item 1', price: 100 },
    item2: { id: 2, name: 'Item 2', price: 200 }
  }
};
```

## Unique Dates Per Test

Prevent conflicts by using unique dates:

```typescript
// test-dates.ts
export const TEST_DATES = {
  'TEST-1.1': { year: 2025, month: 8, day: 1 },
  'TEST-1.2': { year: 2025, month: 8, day: 2 },
  'TEST-2.1': { year: 2025, month: 8, day: 11 }
};
```

## Best Practices

1. **Isolate test data** - each test should have its own data
2. **Use unique identifiers** - prevent cross-test conflicts
3. **Clean up after tests** - delete created records
4. **Document data dependencies** - note what data each test needs
