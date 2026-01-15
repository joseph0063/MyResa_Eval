---
name: code-analyzer
description: Analyzes a codebase to document file purposes and suggest actions like refactoring or removal. Use when asked to analyze a codebase, create a file analysis strategy, or document the roles of files in a project. It is designed to be iterative and self-improving.
---
# Codebase Analysis Skill

This skill provides a systematic process for analyzing a codebase. It is designed to be iterative and self-improving.

## Tools

### Fast Context (`code_search`)

Use `code_search` for **initial discovery** when:
- You don't know where a feature/concept is implemented
- You need to find all files related to a cross-cutting concern
- You're identifying entry points before detailed analysis

### Tool Selection Guide

| Tool | When to Use |
|------|-------------|
| `code_search` | Initial discovery, finding entry points, cross-cutting concerns |
| `grep_search` | Finding specific patterns, usages, callers |
| `read_file` | Full file analysis - read in batches of 4-5 for efficiency |
| `find_by_name` | Listing files in a directory, verifying file existence |

---

## Workflow Steps

### Step 1: Preparation

Before starting analysis:
- [ ] Define scope: which directory/chunk to analyze
- [ ] Note current progress
- [ ] Use `code_search` to identify related files if scope is unclear

### Step 2: Per-File Analysis

For **each file** in the current chunk:
- [ ] **Read** file content completely
- [ ] **Purpose** - What does this file do?
- [ ] **Status** - Custom / Library / Utility?
- [ ] **Action** - Keep / Refactor / Remove / Investigate?
- [ ] **Notes** - Dependencies, quirks, context

### Step 3: Findings Check (Per File)

Ask these questions for each file:
- [ ] **Logic Issue?** - Duplicated? Overly complex (>80 lines or 3+ responsibilities)?
- [ ] **Dead Code?** - Empty files? Unused exports? Orphaned code?
- [ ] **Security Concern?** - Unsanitized input? Missing escaping? Raw SQL?
- [ ] **Performance Issue?** - N+1 queries? Unnecessary loops?

### Step 4: Record Findings

**ALWAYS update findings** - even if no issues found:
- Add entry to appropriate Findings table
- Note any positive patterns worth highlighting

### Step 5: Chunk Completion

After analyzing all files in current chunk:
- [ ] Update Analysis Progress count
- [ ] Mark chunk as complete
- [ ] Get user feedback before proceeding

---

## CRITICAL: Plan Update Discipline

**Rule: One Step, One Plan Update**

After completing EACH step:
1. **Immediately call `update_plan`** - Mark that step as completed
2. **Mark next step as `in_progress`**
3. **Never batch-update** - Do NOT mark multiple steps complete in one call

---

## Self-Improvement Protocol

This skill is designed to learn and adapt.

> **Core Directive:** After any failure or incorrect step, this skill **must** be updated to incorporate the lesson learned.
