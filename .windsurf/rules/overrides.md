---
trigger: always_on
---
---
description: Technical environment rules for Mini Movies (PHP + Bootstrap)
globs: "**/*.{php,js,css}"
---

# Explicit Confirmation Required (Strict Scope)

**NEVER do anything not explicitly requested.** Only perform the exact task the user asked for.

**Mandatory Confirmation:**
- If you identify improvements, optimizations, or related changes → **ASK FIRST**
- If you think something "should" also be done → **ASK FIRST**
- If you notice a bug or issue while working → **REPORT IT, do NOT fix it automatically**

**Format for Suggestions:**
> "I noticed [issue/improvement]. Would you like me to [proposed action]?"

**Do NOT:**
- Add "bonus" features or fixes
- Refactor code beyond what was requested
- "Clean up" files without being asked
- Assume related tasks should be bundled together

**Always come back to the user for confirmation before doing anything outside the explicit request.**


# Technical Constraints
- All code must be strictly compatible with **PHP 8.1**.
- This repository is a **plain PHP** codebase (no frameworks).
- Use **PDO** with prepared statements for all database queries.
- Follow PSR-12 coding style where practical.


# File Deletion Safety (Mandatory Backup-First)

**NEVER delete files directly.** Always follow this workflow:

1. **Rename to backup:** Before any deletion, rename the file to `filename.backup` or `filename.YYYYMMDD.bak`
2. **STOP AND REPORT:** Output to user: "Backup created at [path]. Please test [specific feature]. Reply 'delete' when confirmed working."
3. **WAIT FOR USER REPLY:** Do NOT proceed until user explicitly replies with confirmation
4. **Only then delete:** After user confirmation, delete the backup file

**HARD STOP RULE:**
After creating a backup, you MUST STOP and wait for user response. Do NOT:
- Delete in the same turn as backup creation
- Assume "safe" based on your own analysis
- Batch multiple deletions without per-file confirmation


# Documentation Standards

## Source of Truth
- **Prioritize DocBlocks:** Always prioritize information in PHP DocBlocks (`@param`, `@return`, `@throws`) over inferred logic.
- **Context Pinning:** Before refactoring any class, read the class-level DocBlock to understand its role.

## DocBlock Format (Mandatory)

Every PHP/JS file SHOULD have a file-level docblock:

**PHP Format:**
```php
<?php
/**
 * Brief description of file purpose
 * 
 * Extended description if needed (what it does, key methods, etc.)
 * 
 * @package     MiniMovies\Controllers
 * @requires    PHP 8.1
 * @see         Related files or documentation
 */
```

**JavaScript Format:**
```javascript
/**
 * Brief description of file purpose
 * 
 * Extended description if needed.
 * 
 * @module      MiniMovies/Favorites
 * @see         Related files
 */
```


# Security Boundaries (PHP)
- Validate email format server-side
- Minimum password length: 8 characters
- **ALWAYS** use `password_hash()` for storing passwords
- **ALWAYS** use `password_verify()` for checking passwords
- **ALWAYS** use PDO prepared statements (NEVER concatenate user input into SQL)
- **ALWAYS** use `htmlspecialchars()` when outputting user data
- All POST forms must include CSRF token

# Error Handling
- Display user-friendly error messages
- Log detailed errors for debugging (not to user)
- Use flash messages for success/error feedback


# JavaScript Rules (Project-Specific)
- Keep JS minimal - Bootstrap handles most UI
- Error Handling: Wrap AJAX/fetch calls with `.catch()` blocks; show user-safe messages
- DOM & XSS Safety: Never inject unsanitized HTML; prefer `textContent`
- Asynchrony: Prefer `async/await` with try/catch
- Data Validation: Validate inputs from forms and server responses; guard against undefined/null/NaN


# Database Safety

## No Direct Database Deletions
**Prefer deleting data through the UI when possible.**

**Exception:** Only use direct SQL DELETE when:
1. Data is corrupted AND
2. There is absolutely no way to delete it from the UI

**Reason:** Direct database deletions can break referential integrity.


# MCP & Playwright Testing Rules

## No Automatic MCP Browser Usage
**NEVER use MCP Playwright or Browser MCP tools unless the user explicitly instructs you to.**

Wait for explicit instruction like: "use MCP to test this", "open the browser and check", "use playwright MCP".

## MCP First, Guessing Never
When debugging UI issues:
1. **NEVER guess** the cause
2. **ALWAYS use MCP browser** to see actual state first
3. **Verify actual UI state** with `mcp4_browser_snapshot()`
4. **Only then** diagnose based on what MCP shows

## MCP Browser Debugging
**Tool Selection:** Prefer Playwright MCP (`mcp4_browser_*`) over Visual-Browser MCP for reliability.

**MCP Capabilities Comparison:**

| Feature | Playwright (mcp4) | Visual-Browser (mcp6) |
|---------|-------------------|----------------------|
| Long waits (>30s) | Works | Timeout |
| Console logs | Captured | Empty |
| Network requests | URL + status | Not available |
| Click reliability | Stable | Timeout on AJAX |
| Custom code injection | `browser_run_code` | Not available |


# File Scope Restrictions

## Default rule
- Work within the project directory.

## Allowed files
- `.windsurf/rules/overrides.md`
- `README.md`
- All project source files


# Documentation Maintenance

## Documentation Files
- `README.md` - Project overview, setup, admin rule, shortcuts


# Tool Reliability Lessons

## File Write Tool Failures

**Issue:** The standard `write_to_file` tool can silently fail, creating empty files.

**Workaround:** When `write_to_file` fails:
1. Verify file content with `read_file` after write
2. If empty, use `mcp1_write_file` (MCP filesystem tool) as fallback
3. Verify again after MCP write
