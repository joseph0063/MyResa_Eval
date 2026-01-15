---
description: Run normal/individual tests
---

# Run Normal Tests

Use the `@playwright-test-runner` skill to execute individual tests.

## Steps

1. Invoke the skill: `@playwright-test-runner`

2. Navigate to the tests directory:
```powershell
cd tests
```

// turbo
3. Run normal tests with 4 workers:
```powershell
npx playwright test --workers=4 --reporter=list
```

4. After tests complete:
   - Report pass/fail summary
   - If failures exist, list failed test IDs
   - Ask user if they want to debug specific failures

## Re-running Failed Tests

If tests fail, re-run only the failed ones:
```powershell
npx playwright test --grep "TEST-X.X|TEST-Y.Y" --reporter=list
```

## Debug Mode

For interactive debugging:
```powershell
npx playwright test --grep "TEST-X.X" --headed --debug
```
