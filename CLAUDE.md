# CLAUDE.md

Project-level context for Claude Code. Keep this short.

## Orientation

- **Project:** broedertrouw — bilingual (NL default / DE) marketing site for the stevenklipper Broedertrouw, a historic sailing charter ship in Hoorn (NL). School classes are the primary audience.
- **Workspace root:** the WordPress install root. Custom code lives in [wp-content/themes/broedertrouw/](wp-content/themes/broedertrouw/) (Blocksy child theme). This repo tracks the child theme and root config only — WordPress core and all plugins (premium: Blocksy Companion Pro, Polylang Pro, FluentForm) are gitignored and managed separately.
- **Local site:** https://broedertrouw.test (Valet-style, http redirects to https). WP-CLI works from the repo root.

## Architecture rules

- **Never place a header or footer directly into the theme.** Use the Blocksy header/footer builder (customizer `theme_mods_broedertrouw`: `header_placements` / `footer_placements`) and WordPress menus.
- **Languages:** Polylang Pro. NL is the default language (hidden from URLs), DE is secondary under `/de/`. Menus are assigned per language via Polylang's menu-location handling; widget blocks carry a `pll_lang` attribute so each sidebar widget renders only in its language.
- **Language switcher:** show ONLY the inactive language, as flag + code (Blocksy header language-switcher element with `hide_current_language`).
- **Design source:** Claude Design project "Broedertrouw website redesign" (`240887f9-6f33-462f-96a0-7eb07837b09d`) — one `*.dc.html` per page plus a CLAUDE.md with binding design rules (maritime blues, navy `#0F3A5C`, Archivo + Source Sans 3, informal Du/je tone, never em dashes in copy).
- Page content is built with Gutenberg blocks styled by the child theme's `style.css`; keep sections buildable/editable in the editor rather than hardcoded in PHP.

## Verifying UI changes

Verify rendered output on https://broedertrouw.test via the Playwright MCP ([.mcp.json](.mcp.json)) — navigate, screenshot, snapshot. The cert is self-signed; ignore HTTPS errors.

## Commit conventions

Same as kingnature ([trafficflowgmbh/kingnature](https://github.com/trafficflowgmbh/kingnature)):

- Format: `[Prefix] Brief description in present tense` — prefixes `[Update]` (default), `[Fix]`, `[Add]`, `[Remove]`, `[Refactor]`, `[Cleanup]`, `[Docs]`.
- Subject under 72 chars, no trailing period. Multi-change commits get a `-` bullet body.
- **No Claude / AI co-author trailers.**
- Never commit directly to `master` — cut a feature branch (`add/<slug>`, `fix/<slug>`, `update/<slug>`) from latest `master`.
- Don't commit `wp-config.php`, `.env*`, or premium plugin code (public repo).
