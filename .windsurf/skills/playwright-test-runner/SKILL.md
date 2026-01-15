---
name: playwright-test-runner
description: Run and debug Playwright E2E tests. Use when running tests, debugging test failures, or understanding test patterns. It is designed to be iterative and self-improving.
---

# Playwright Test Runner

## Mission

Execute and debug E2E tests, ensuring flows work correctly.

## Scope & Responsibilities

| This Skill Does | This Skill Does NOT |
|-----------------|---------------------|
| Run existing tests | Write new tests (use `@playwright-test-writing`) |
| Debug test failures | Debug application bugs (use `@debugging-with-playwright-mcp`) |
| Explain test patterns | Implement application code |
| Track test results | Deploy to production |

## When to Use

**Invoke this skill when:**
- Running the test suite or specific tests
- A test fails and you need to understand why
- Need to know selector patterns
- Updating test results documentation

## Core Directives

1. **Never re-run full suite after initial run** — only re-run failed tests
2. **MCP before guessing** — always verify with `@debugging-with-playwright-mcp` before assuming cause
3. **Distinguish test failure from application bug** — when a test fails, first investigate whether the failure reveals an actual application bug or is just a test issue

## Quick Start

```powershell
# Run single test
npx playwright test --grep "TEST-1.1" --reporter=list

# Run all tests
npx playwright test --workers=4 --reporter=list

# Debug mode (visible browser)
npx playwright test --grep "TEST-1.1" --headed --debug
```

## Test Commands

| Option | Description |
|--------|-------------|
| `--grep "pattern"` | Filter by test name pattern |
| `--workers=4` | Run with 4 parallel workers |
| `--workers=1` | Run sequentially (for debugging) |
| `--reporter=list` | Show test names as they run |
| `--headed` | Run with visible browser |
| `--debug` | Interactive debug mode |

## Common Failures & Fixes

| Symptom | Likely Cause | Fix |
|---------|--------------|-----|
| Timeout on element | Loading overlay blocking | Wait for loading hidden |
| Wrong element clicked | nth() index shifted | Use MCP to verify current indices |
| Modal closed unexpectedly | AJAX error occurred | Check browser console logs |
| Dropdown options empty | AJAX not completed | Wait for options loaded |
| Validation error not found | Error text differs | Use MCP to capture actual text |

## Verification Rules

1. **Extract EXACT values** - not `toContainText('200')` but exact match
2. **Count items explicitly** - `expect(rowCount).toBe(2)`
3. **Calculate totals** - verify math is correct
4. **Verify each line item** - loop through and check

## Loading States

```javascript
// Wait for loading overlay
await page.waitForSelector('.loading', { state: 'hidden', timeout: 15000 });

// Wait for dropdown options to load
await page.waitForSelector('.select-option', { timeout: 15000 });
```

## Related Skills

- **Writing new tests**: Use `@playwright-test-writing`
- **Debugging with browser**: Use `@debugging-with-playwright-mcp`

---

## Self-Improvement Protocol

This skill is designed to learn and adapt.

> **Core Directive:** After any failure or incorrect step, this skill **must** be updated to incorporate the lesson learned.
