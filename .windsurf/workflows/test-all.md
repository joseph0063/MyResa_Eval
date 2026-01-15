---
description: Run all Playwright tests
---

# Run All Playwright Tests

Use the `@playwright-test-runner` skill to execute and manage the test run.

## Steps

1. Invoke the skill: `@playwright-test-runner`

2. Navigate to the tests directory:
```powershell
cd tests
```

// turbo
3. Run all tests with 4 workers:
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

## Failure Reproduction Guide

For each failed test, provide the user with:

### 1. Manual Reproduction Steps
- **URL to navigate:** The page URL
- **Exact click sequence:** List each UI action
- **Test data used:** Values from test config
- **Expected vs Actual:** What should happen vs what the test detected

### 2. Problematic Behavior to Notice
Guide user on what to observe:
- **Dropdown issues:** "Check if dropdown shows options or stays empty"
- **Timing issues:** "Notice if loading spinner appears/disappears correctly"
- **Validation errors:** "Look for error messages after form submit"
- **Data persistence:** "Verify record appears in list after save"

### 3. Output Format
```
## Failed Test: TEST-X.X - [Test Name]

**To Reproduce:**
1. Go to: [URL]
2. Click [button/element]
3. Fill [value] in [field]
4. ...

**What to Check:**
- [ ] Does [element] show [expected state]?
- [ ] Does [action] trigger [expected response]?
- [ ] Is [data] saved correctly?

**Test Expected:** [description]
**Test Got:** [actual error/state]
```
