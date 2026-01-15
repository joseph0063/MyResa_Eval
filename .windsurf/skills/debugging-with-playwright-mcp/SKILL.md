---
name: debugging-with-playwright-mcp
description: Debug web UI issues and confirm fixes using MCP browser. Use when (1) investigating a bug to see actual UI state, (2) confirming a fix works, (3) test fails and need to verify selectors, (4) need to check AJAX/network behavior, or (5) diagnosing timing/visibility issues. It is designed to be iterative and self-improving.
---

# MCP Browser Debugging

## Mission

Provide real-time browser inspection to debug UI issues, verify fixes, and diagnose test failures - eliminating guesswork with actual browser state.

## Scope & Responsibilities

| This Skill Does | This Skill Does NOT |
|-----------------|---------------------|
| Investigate bugs in browser | Write new tests |
| Confirm fixes work | Run test suites |
| Verify selectors and element state | Implement application code |
| Inspect network/AJAX requests | Deploy or commit changes |
| Diagnose timing/visibility issues | |

## When to Use

**Invoke this skill when:**
- Investigating a reported bug
- Confirming a code fix works before committing
- A test fails and you need to see actual UI state
- Checking if AJAX loaded correctly
- Diagnosing timing or visibility issues
- Verifying selector indices

## Core Directive

> **MCP First, Guessing Never.**
> 
> Never assume the cause of a bug or test failure. Always verify actual browser state with MCP before diagnosing.

## Workflow

### Step 1: Navigate
`mcp_browser_navigate({ url: "http://localhost/page" })`

### Step 2: Snapshot (See Current State)
`mcp_browser_snapshot()`
Returns accessibility tree with element refs.

### Step 3: Perform Action
`mcp_browser_click({ ref: "ref-from-snapshot" })`

### Step 4: Verify Result
Take another snapshot to confirm state changed as expected.

### Step 5: Repeat or Conclude
Continue until bug is reproduced or fix is confirmed.

## MCP Tools Reference

| Tool | Purpose |
|------|---------|
| `mcp_browser_navigate` | Go to URL |
| `mcp_browser_snapshot` | See current DOM/accessibility tree |
| `mcp_browser_click` | Click element by ref or selector |
| `mcp_browser_type` | Type text into element |
| `mcp_browser_fill` | Fill form field |
| `mcp_browser_select` | Select dropdown option |
| `mcp_browser_press` | Press keyboard key |
| `mcp_browser_console` | Get console logs |
| `mcp_browser_network` | Get network requests |
| `mcp_browser_run_code` | Execute custom page code |

## Debugging Patterns

### Bug Investigation
1. Navigate to page where bug occurs
2. Reproduce the bug step-by-step
3. Snapshot at each step to see state
4. Check console for JS errors
5. Inspect network for failed requests

### Confirm Fix
1. Navigate to affected page
2. Perform the action that was broken
3. Snapshot to verify correct behavior
4. Test edge cases
5. Confirm console is error-free

### Test Failure Debug
1. Navigate to test's target page
2. Perform exact test steps manually
3. Snapshot to see actual state vs expected
4. Identify selector/timing mismatch

### AJAX/Loading Debug
1. Trigger the AJAX action
2. Wait for loading indicator
3. Snapshot when complete
4. Verify data loaded correctly

## Verification Checklist

Before concluding diagnosis:
- [ ] Element present in accessibility tree?
- [ ] Element visible (not hidden)?
- [ ] Correct content displayed?
- [ ] Console error-free?
- [ ] Network requests successful?

## Persistent Session Strategy

Keep MCP browser open during debugging for rapid iteration:
1. Navigate once to target page
2. Perform multiple snapshots/actions
3. Reset only when page stuck or testing different flow

## Database Query Verification

**Rule:** When a database query returns an unexpected empty result, do NOT immediately conclude there's a data saving problem.

**Workflow:**
1. Query returns empty/unexpected result
2. **Go back to MCP browser** to verify data still exists in UI
3. If data is visible in UI -> data exists, query is wrong
4. If data is NOT visible in UI -> data was deleted externally
5. Only conclude "data saving problem" if data was never created

## Related Skills

- **Writing tests**: Use `@playwright-test-writing`
- **Running tests**: Use `@playwright-test-runner`

---

## Self-Improvement Protocol

This skill is designed to learn and adapt.

> **Core Directive:** After any failure or incorrect step, this skill **must** be updated to incorporate the lesson learned.
