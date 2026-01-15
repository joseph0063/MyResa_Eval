---
name: docblock-rewrite
description: Structured workflow for rewriting docblocks using codebase analysis. Use when enhancing PHP docblocks so Cascade can reason about intent, invariants, and validation paths.
---

# Docblock Rewrite Workflow

## Quick Start
1. Identify the file/function needing a richer docblock.
2. Confirm purpose from codebase analysis or file inspection.
3. Apply the rewrite checklist below.

## Core Workflow

1. **Scope & Ownership**
   - Confirm file classification (Custom vs Library vs Utility).
   - Note any recommended actions (Keep/Refactor/Investigate).

2. **Trace Context**
   - Locate neighboring files (helpers, services, views).
   - Capture: known issues, invariants, and positive patterns.

3. **Source Verification**
   - Open the actual PHP file before editing (Zero-Assumption Architecture).
   - Identify inputs and outputs that must be documented.

4. **Docblock Construction**
   - **Summary:** One sentence stating purpose.
   - **Parameters/Returns:** Type hints, allowed values, failure forms.
   - **Side Effects:** DB tables touched, hooks triggered, JS dependencies.
   - **Invariants & Preconditions:** Ordering rules, constraints.
   - **References:** Use `@see` to point at related files.

5. **Consistency Checks**
   - Ensure terminology is consistent.
   - Confirm docblock reflects current behavior.

6. **Post-Update Actions**
   - If rewriting surfaced new insights, update documentation.

## Docblock Format (PHP)

```php
<?php
/**
 * Brief description of file purpose
 * 
 * Extended description if needed.
 * 
 * @package     MiniMovies\Controllers
 * @requires    PHP 8.1
 * @see         Related files or documentation
 */
```

## Docblock Format (JavaScript)

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
