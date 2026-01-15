---
description: Interact with the git MCP server for version control operations.
---

# Git MCP Server

Perform Git operations on repositories.

## Available Tools

### `mcp2_git_status`

Show working tree status (staged, unstaged, untracked files).

**Parameters:**
- `path` (optional): Path to repository (defaults to working dir)
- `includeUntracked` (optional): Include untracked files (default: true)

```xml
<mcp2_git_status>
{"path": "/path/to/repo"}
</mcp2_git_status>
```

---

### `mcp2_git_log`

View commit history with optional filtering.

**Parameters:**
- `path` (optional): Path to repository
- `maxCount` (optional): Max commits to return (1-1000)
- `author` (optional): Filter by author
- `since` (optional): Commits after date (ISO 8601)
- `until` (optional): Commits before date
- `grep` (optional): Filter by message pattern
- `filePath` (optional): Commits affecting specific file
- `oneline` (optional): Abbreviated output
- `stat` (optional): Include file change stats
- `patch` (optional): Include full diff

```xml
<!-- Last 10 commits -->
<mcp2_git_log>
{"path": "/path/to/repo", "maxCount": 10}
</mcp2_git_log>

<!-- Commits by author -->
<mcp2_git_log>
{"path": "/path/to/repo", "author": "john@example.com", "maxCount": 20}
</mcp2_git_log>

<!-- Commits affecting a file -->
<mcp2_git_log>
{"path": "/path/to/repo", "filePath": "src/main.js", "maxCount": 10}
</mcp2_git_log>

<!-- With stats -->
<mcp2_git_log>
{"path": "/path/to/repo", "maxCount": 5, "stat": true}
</mcp2_git_log>
```

---

### `mcp2_git_diff`

View differences between commits, branches, or working tree.

**Parameters:**
- `path` (optional): Path to repository
- `source` (optional): Source commit/branch
- `target` (optional): Target commit/branch
- `staged` (optional): Show staged changes
- `paths` (optional): Limit to specific files
- `nameOnly` (optional): Show only filenames
- `stat` (optional): Show diffstat summary
- `contextLines` (optional): Lines of context (default: 3)

```xml
<!-- Unstaged changes -->
<mcp2_git_diff>
{"path": "/path/to/repo"}
</mcp2_git_diff>

<!-- Staged changes -->
<mcp2_git_diff>
{"path": "/path/to/repo", "staged": true}
</mcp2_git_diff>

<!-- Between branches -->
<mcp2_git_diff>
{"path": "/path/to/repo", "source": "main", "target": "feature-branch"}
</mcp2_git_diff>

<!-- Specific file -->
<mcp2_git_diff>
{"path": "/path/to/repo", "paths": ["src/main.js"]}
</mcp2_git_diff>
```

---

### `mcp2_git_add`

Stage files for commit.

**Parameters:**
- `path` (optional): Path to repository
- `files` (required): Array of file paths to stage
- `all` (optional): Stage all changes
- `update` (optional): Stage only modified/deleted (skip untracked)

```xml
<!-- Stage specific files -->
<mcp2_git_add>
{"path": "/path/to/repo", "files": ["src/main.js", "src/utils.js"]}
</mcp2_git_add>

<!-- Stage all changes -->
<mcp2_git_add>
{"path": "/path/to/repo", "files": ["."], "all": true}
</mcp2_git_add>
```

---

### `mcp2_git_commit`

Create a new commit with staged changes.

**Parameters:**
- `path` (optional): Path to repository
- `message` (required): Commit message
- `amend` (optional): Amend previous commit
- `allowEmpty` (optional): Allow empty commit
- `filesToStage` (optional): Files to stage before committing

```xml
<mcp2_git_commit>
{"path": "/path/to/repo", "message": "feat: Add new feature"}
</mcp2_git_commit>

<!-- Stage and commit in one -->
<mcp2_git_commit>
{"path": "/path/to/repo", "message": "fix: Bug fix", "filesToStage": ["src/fix.js"]}
</mcp2_git_commit>
```

---

### `mcp2_git_branch`

Manage branches: list, create, delete, rename.

**Parameters:**
- `path` (optional): Path to repository
- `operation` (optional): "list", "create", "delete", "rename", "show-current"
- `name` (optional): Branch name for create/delete/rename
- `newName` (optional): New name for rename
- `all` (optional): Show local and remote branches
- `remote` (optional): Show only remote branches

```xml
<!-- List branches -->
<mcp2_git_branch>
{"path": "/path/to/repo", "operation": "list"}
</mcp2_git_branch>

<!-- Show current branch -->
<mcp2_git_branch>
{"path": "/path/to/repo", "operation": "show-current"}
</mcp2_git_branch>

<!-- Create branch -->
<mcp2_git_branch>
{"path": "/path/to/repo", "operation": "create", "name": "feature-new"}
</mcp2_git_branch>

<!-- Delete branch -->
<mcp2_git_branch>
{"path": "/path/to/repo", "operation": "delete", "name": "old-branch"}
</mcp2_git_branch>
```

---

### `mcp2_git_checkout`

Switch branches or restore files.

**Parameters:**
- `path` (optional): Path to repository
- `target` (required): Branch name or commit hash
- `createBranch` (optional): Create new branch
- `paths` (optional): Specific files to checkout

```xml
<!-- Switch branch -->
<mcp2_git_checkout>
{"path": "/path/to/repo", "target": "main"}
</mcp2_git_checkout>

<!-- Create and switch -->
<mcp2_git_checkout>
{"path": "/path/to/repo", "target": "feature-new", "createBranch": true}
</mcp2_git_checkout>

<!-- Restore file -->
<mcp2_git_checkout>
{"path": "/path/to/repo", "target": "HEAD", "paths": ["src/main.js"]}
</mcp2_git_checkout>
```

---

### `mcp2_git_merge`

Merge branches together.

**Parameters:**
- `path` (optional): Path to repository
- `branch` (required): Branch to merge
- `message` (optional): Custom merge commit message
- `noFastForward` (optional): Always create merge commit
- `squash` (optional): Squash commits into one
- `abort` (optional): Abort in-progress merge

```xml
<mcp2_git_merge>
{"path": "/path/to/repo", "branch": "feature-branch"}
</mcp2_git_merge>

<!-- No fast-forward -->
<mcp2_git_merge>
{"path": "/path/to/repo", "branch": "feature", "noFastForward": true}
</mcp2_git_merge>
```

---

### `mcp2_git_pull`

Pull changes from remote.

**Parameters:**
- `path` (optional): Path to repository
- `remote` (optional): Remote name (default: origin)
- `branch` (optional): Branch name
- `rebase` (optional): Use rebase instead of merge

```xml
<mcp2_git_pull>
{"path": "/path/to/repo"}
</mcp2_git_pull>

<!-- With rebase -->
<mcp2_git_pull>
{"path": "/path/to/repo", "rebase": true}
</mcp2_git_pull>
```

---

### `mcp2_git_push`

Push changes to remote.

**Parameters:**
- `path` (optional): Path to repository
- `remote` (optional): Remote name
- `branch` (optional): Branch name
- `setUpstream` (optional): Set upstream tracking
- `force` (optional): Force push (dangerous!)
- `tags` (optional): Push all tags

```xml
<mcp2_git_push>
{"path": "/path/to/repo"}
</mcp2_git_push>

<!-- Set upstream -->
<mcp2_git_push>
{"path": "/path/to/repo", "branch": "feature", "setUpstream": true}
</mcp2_git_push>
```

---

### `mcp2_git_fetch`

Fetch updates from remote without merging.

**Parameters:**
- `path` (optional): Path to repository
- `remote` (optional): Remote name
- `prune` (optional): Remove stale remote-tracking refs
- `tags` (optional): Fetch all tags

```xml
<mcp2_git_fetch>
{"path": "/path/to/repo", "prune": true}
</mcp2_git_fetch>
```

---

### `mcp2_git_reset`

Reset HEAD to specified state.

**Parameters:**
- `path` (optional): Path to repository
- `mode` (optional): "soft", "mixed", "hard"
- `target` (optional): Commit to reset to
- `paths` (optional): Specific files to reset

```xml
<!-- Unstage all -->
<mcp2_git_reset>
{"path": "/path/to/repo", "mode": "mixed"}
</mcp2_git_reset>

<!-- Hard reset to commit -->
<mcp2_git_reset>
{"path": "/path/to/repo", "mode": "hard", "target": "HEAD~1"}
</mcp2_git_reset>

<!-- Unstage specific file -->
<mcp2_git_reset>
{"path": "/path/to/repo", "paths": ["src/main.js"]}
</mcp2_git_reset>
```

---

### `mcp2_git_stash`

Manage stashes.

**Parameters:**
- `path` (optional): Path to repository
- `mode` (optional): "list", "push", "pop", "apply", "drop", "clear"
- `message` (optional): Stash message
- `includeUntracked` (optional): Include untracked files
- `stashRef` (optional): Stash reference (e.g., "stash@{0}")

```xml
<!-- List stashes -->
<mcp2_git_stash>
{"path": "/path/to/repo", "mode": "list"}
</mcp2_git_stash>

<!-- Save stash -->
<mcp2_git_stash>
{"path": "/path/to/repo", "mode": "push", "message": "WIP: feature"}
</mcp2_git_stash>

<!-- Apply stash -->
<mcp2_git_stash>
{"path": "/path/to/repo", "mode": "pop"}
</mcp2_git_stash>
```

---

### `mcp2_git_show`

Show details of a git object (commit, tree, blob, tag).

**Parameters:**
- `path` (optional): Path to repository
- `object` (required): Commit hash, branch, or tag
- `filePath` (optional): View specific file at commit
- `stat` (optional): Show diffstat

```xml
<mcp2_git_show>
{"path": "/path/to/repo", "object": "HEAD"}
</mcp2_git_show>

<!-- Show file at commit -->
<mcp2_git_show>
{"path": "/path/to/repo", "object": "abc123", "filePath": "src/main.js"}
</mcp2_git_show>
```

---

### `mcp2_git_blame`

Show line-by-line authorship.

**Parameters:**
- `path` (optional): Path to repository
- `file` (required): File path
- `startLine` (optional): Start line (1-indexed)
- `endLine` (optional): End line

```xml
<mcp2_git_blame>
{"path": "/path/to/repo", "file": "src/main.js"}
</mcp2_git_blame>

<!-- Specific lines -->
<mcp2_git_blame>
{"path": "/path/to/repo", "file": "src/main.js", "startLine": 10, "endLine": 20}
</mcp2_git_blame>
```

---

### `mcp2_git_tag`

Manage tags.

**Parameters:**
- `path` (optional): Path to repository
- `mode` (optional): "list", "create", "delete"
- `tagName` (optional): Tag name
- `message` (optional): Tag message (creates annotated tag)
- `commit` (optional): Commit to tag

```xml
<!-- List tags -->
<mcp2_git_tag>
{"path": "/path/to/repo", "mode": "list"}
</mcp2_git_tag>

<!-- Create tag -->
<mcp2_git_tag>
{"path": "/path/to/repo", "mode": "create", "tagName": "v1.0.0", "message": "Release 1.0.0"}
</mcp2_git_tag>
```

---

### `mcp2_git_remote`

Manage remote repositories.

**Parameters:**
- `path` (optional): Path to repository
- `mode` (optional): "list", "add", "remove", "rename", "get-url", "set-url"
- `name` (optional): Remote name
- `url` (optional): Remote URL
- `newName` (optional): New name for rename

```xml
<!-- List remotes -->
<mcp2_git_remote>
{"path": "/path/to/repo", "mode": "list"}
</mcp2_git_remote>

<!-- Add remote -->
<mcp2_git_remote>
{"path": "/path/to/repo", "mode": "add", "name": "upstream", "url": "https://github.com/org/repo.git"}
</mcp2_git_remote>
```

---

### `mcp2_git_rebase`

Rebase commits onto another branch.

**Parameters:**
- `path` (optional): Path to repository
- `upstream` (required for start): Branch to rebase onto
- `mode` (optional): "start", "continue", "abort", "skip"

```xml
<mcp2_git_rebase>
{"path": "/path/to/repo", "upstream": "main"}
</mcp2_git_rebase>

<!-- Continue after resolving conflicts -->
<mcp2_git_rebase>
{"path": "/path/to/repo", "mode": "continue"}
</mcp2_git_rebase>
```

---

### `mcp2_git_cherry_pick`

Apply specific commits to current branch.

**Parameters:**
- `path` (optional): Path to repository
- `commits` (required): Array of commit hashes
- `noCommit` (optional): Stage changes only

```xml
<mcp2_git_cherry_pick>
{"path": "/path/to/repo", "commits": ["abc123", "def456"]}
</mcp2_git_cherry_pick>
```

---

### `mcp2_git_clean`

Remove untracked files.

**Parameters:**
- `path` (optional): Path to repository
- `force` (required): Must be true to execute
- `directories` (optional): Also remove directories
- `dryRun` (optional): Preview what would be removed

```xml
<!-- Preview -->
<mcp2_git_clean>
{"path": "/path/to/repo", "force": true, "dryRun": true}
</mcp2_git_clean>

<!-- Actually clean -->
<mcp2_git_clean>
{"path": "/path/to/repo", "force": true, "directories": true}
</mcp2_git_clean>
```

---

### `mcp2_git_reflog`

View reference logs (recover lost commits).

**Parameters:**
- `path` (optional): Path to repository
- `maxCount` (optional): Max entries
- `ref` (optional): Specific reference (default: HEAD)

```xml
<mcp2_git_reflog>
{"path": "/path/to/repo", "maxCount": 20}
</mcp2_git_reflog>
```

---

### `mcp2_git_init`

Initialize a new repository.

**Parameters:**
- `path` (optional): Path for new repository
- `initialBranch` (optional): Name of initial branch

```xml
<mcp2_git_init>
{"path": "/path/to/new-repo", "initialBranch": "main"}
</mcp2_git_init>
```

---

### `mcp2_git_clone`

Clone a repository.

**Parameters:**
- `url` (required): Repository URL
- `localPath` (required): Local destination
- `branch` (optional): Specific branch
- `depth` (optional): Shallow clone depth

```xml
<mcp2_git_clone>
{"url": "https://github.com/org/repo.git", "localPath": "/path/to/clone"}
</mcp2_git_clone>

<!-- Shallow clone -->
<mcp2_git_clone>
{"url": "https://github.com/org/repo.git", "localPath": "/path/to/clone", "depth": 1}
</mcp2_git_clone>
```

---

### `mcp2_git_set_working_dir`

Set default working directory for git operations.

**Parameters:**
- `path` (required): Repository path
- `includeMetadata` (optional): Include repo status in response

```xml
<mcp2_git_set_working_dir>
{"path": "/path/to/repo", "includeMetadata": true}
</mcp2_git_set_working_dir>
```

---

### `mcp2_git_worktree`

Manage multiple working trees.

**Parameters:**
- `path` (optional): Path to repository
- `mode` (optional): "list", "add", "remove", "move", "prune"
- `worktreePath` (optional): Path for new worktree
- `branch` (optional): Branch for worktree

```xml
<!-- List worktrees -->
<mcp2_git_worktree>
{"path": "/path/to/repo", "mode": "list"}
</mcp2_git_worktree>

<!-- Add worktree -->
<mcp2_git_worktree>
{"path": "/path/to/repo", "mode": "add", "worktreePath": "/path/to/worktree", "branch": "feature"}
</mcp2_git_worktree>
```
