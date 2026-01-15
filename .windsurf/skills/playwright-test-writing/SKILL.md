---
name: playwright-test-writing
description: Write Playwright E2E tests using MCP browser for selector discovery and verification. Use when implementing new tests, discovering selectors, or building test flows step-by-step with real browser state. It is designed to be iterative and self-improving.
---

# Playwright Test Writing Strategy (MCP-Based)

Write reliable Playwright tests by using MCP browser to discover selectors and verify each step in real-time.

---

## Core Principle

**NEVER guess selectors. ALWAYS verify with MCP first.**

```
WRONG: Write test based on assumed selectors → Run → Fix failures
RIGHT: Use MCP to discover exact selectors → Write test → Verify
```

> **EXCEPTION TO GLOBAL RULES:** MCP browser usage is **explicitly allowed and required** 
> for Playwright test writing. This skill overrides the global rule "No Automatic MCP Browser Usage"
> because selector discovery via MCP is fundamental to writing reliable tests.

---

## Core Directives

### 1. Never Re-Run Full Suite After Initial Run

Only re-run **failed tests** using `--grep`:

```powershell
# BAD: Running everything again
npx playwright test

# GOOD: Only failed tests
npx playwright test --grep "TEST-1.2" --reporter=list
```

### 2. MCP Before Guessing

When a test fails:
1. **NEVER guess** the cause
2. **ALWAYS use MCP browser** to simulate the exact failing flow
3. **Verify actual UI state** with `mcp_browser_snapshot()`

### 3. Update Skill Files After Every Fix

**When a test fails and you fix it, IMMEDIATELY update this skill file:**

```
Test fails → Use MCP to diagnose → Fix the test → UPDATE SKILL FILE
```

### 4. Distinguish Test Failure from Application Bug

| Type | Symptoms | Action |
|------|----------|--------|
| **Test issue** | Wrong selector, timing, stale data | Fix the test |
| **Application bug** | Feature broken, data corruption | **Inform user** |

**FORBIDDEN:** Modifying a test to hide a real application bug.

### 5. Wait for Loading Before Interactions

Always wait for loading states before clicking elements:

```typescript
await page.waitForSelector('.loading', { state: 'hidden', timeout: 15000 });
```

### 6. Never Skip/Fail Tests Without MCP Verification

Before marking ANY test as SKIPPED or FAILED:
1. MUST run `mcp_browser_snapshot()` to see exact UI state
2. MUST understand WHY the test failed
3. MUST document the MCP findings

---

## MCP Discovery Workflow

### Phase 1: Setup MCP Session

```javascript
// 1. Navigate to page
mcp_browser_navigate({ url: "http://localhost/page" })

// 2. Take initial snapshot
mcp_browser_snapshot()
```

### Phase 2: Open Modal & Discover Fields

```javascript
// 3. Click button to open modal
mcp_browser_click({ ref: "button-ref-from-snapshot" })

// 4. Snapshot modal
mcp_browser_snapshot()
// → Note ALL element refs and their positions
```

### Phase 3: Fill Each Field & Track State

```javascript
// 5. Click dropdown
mcp_browser_click({ ref: "dropdown-ref" })

// 6. Snapshot dropdown options
mcp_browser_snapshot()

// 7. Select option
mcp_browser_click({ ref: "option-ref" })

// 8. Snapshot again to see new state
```

### Phase 4: Complete Flow Before Writing Code

**NEVER write code until you've completed the entire flow via MCP!**

```
✓ Page loaded
✓ Modal opened
✓ Fields filled
✓ Form submitted
✓ Result verified
```

---

## Selector Patterns

### Dropdowns (nth pattern)

After each selection, remaining empty dropdowns shift:
- First dropdown: `nth(1)`
- After selection: next empty becomes `nth(1)`

```javascript
// Click dropdown
await page.getByRole('combobox', { name: 'Select...' }).nth(1).click();
await page.waitForTimeout(300);

// Select option
await page.getByRole('option', { name: 'Option Text' }).click();
```

### Date Picker

```javascript
// Open datepicker
await page.getByRole('textbox', { name: 'Date' }).click();
await page.waitForTimeout(300);

// Select day
await page.getByRole('cell', { name: '15', exact: true }).first().click();
```

### Buttons

```javascript
await page.getByRole('button', { name: 'Save' }).click();
await page.getByRole('button', { name: 'Cancel' }).click();
```

---

## Verification Patterns

### FORBIDDEN Patterns

```typescript
// ❌ NEVER: Shallow text contains check
await expect(modal).toContainText('item');

// ❌ NEVER: Assumed index without MCP verification
const cell = tableCells[2]; // How do you know it's index 2?
```

### CORRECT Patterns

```typescript
// ✅ CORRECT: Define expected values upfront
const EXPECTED = {
  item: { price: 200 },
  get total() { return this.item.price; }
};

// ✅ CORRECT: Extract from MCP-verified location
const cell = (await tableCells[2].textContent())?.trim(); // Index 2 = verified via MCP
expect(cell).toContain('item');

// ✅ CORRECT: Verify calculation
expect(extractedTotal).toBeCloseTo(EXPECTED.total, 0);
```

---

## Related Skills

- **Running tests**: Use `@playwright-test-runner`
- **Debugging with browser**: Use `@debugging-with-playwright-mcp`

---

## Self-Improvement Protocol

This skill is designed to learn and adapt.

> **Core Directive:** After any failure or incorrect step, this skill **must** be updated to incorporate the lesson learned.
