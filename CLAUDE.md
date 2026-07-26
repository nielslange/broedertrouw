# CLAUDE.md

Project-level context for Claude Code. Keep this short.

## Orientation

- **Project:** broedertrouw — bilingual (NL default / DE) marketing site for the stevenklipper Broedertrouw, a historic sailing charter ship in Hoorn (NL). School classes are the primary audience.
- **Workspace root:** the WordPress install root. Custom code lives in [wp-content/themes/broedertrouw/](wp-content/themes/broedertrouw/) (Blocksy child theme). This repo tracks the child theme and root config only — WordPress core and all plugins (premium: Blocksy Companion Pro, Polylang Pro, FluentForm) are gitignored and managed separately.
- **Local site:** <https://broedertrouw.test> (Valet-style, http redirects to https). WP-CLI works from the repo root.

## Architecture rules

- **All code-level strings are English** — post type and field keys, block names, PHP labels, and i18n source strings. Content is NL (primary) / DE (secondary); translations live in [wp-content/themes/broedertrouw/languages/](wp-content/themes/broedertrouw/languages/). After changing a translatable string run `wp i18n make-pot wp-content/themes/broedertrouw wp-content/themes/broedertrouw/languages/broedertrouw.pot --domain=broedertrouw --exclude=acf-json,bin`, update the `.po` files, then `wp i18n make-mo`.
- **Never place a header or footer directly into the theme.** Use the Blocksy header/footer builder (customizer `theme_mods_broedertrouw`: `header_placements` / `footer_placements`) and WordPress menus.
  - **Footer link columns are menu locations, never nav-menu widgets.** Blocksy ships two footer locations; [includes/footer.php](wp-content/themes/broedertrouw/includes/footer.php) adds a third (`footer_3`) plus a matching builder element in [footer-items/menu-tertiary/](wp-content/themes/broedertrouw/footer-items/menu-tertiary/), following the same approach as [watermantours/1dagzeilen.nl](https://github.com/watermantours/1dagzeilen.nl) (`register_nav_menus` + `blocksy:register_nav_menus:input` + `blocksy:footer:items-paths`). Only the contact column and the bottom-bar switcher stay widgets, because they are prose rather than links.
  - Blocksy's footer menu element has **no title field**, so column headings are generated in CSS from `nav[aria-label="<menu name>"]::before`. Renaming a menu therefore breaks its heading — keep the CSS and the menu names in sync.
  - Menus follow the naming pattern `Main Menu <LANG>`, `Footer Menu Trips <LANG>`, `Footer Menu Info <LANG>`, `Footer Menu Legals <LANG>`, and are assigned per language through Polylang's `nav_menus` option.
  - The sticky header uses Blocksy's default behaviour (`has_sticky_header` only). Its sticky row background is set to the same value as the normal row, because Blocksy otherwise applies its own default palette color and the navy-on-navy result is unreadable.
- **Page content is built with ACF PRO blocks**, not core Gutenberg blocks. One folder per block under [blocks/](wp-content/themes/broedertrouw/blocks/) with `block.json` + `render.php` + `style.css`; [includes/blocks.php](wp-content/themes/broedertrouw/includes/blocks.php) auto-discovers them. No build step. Blocks are namespaced `bt/` and grouped under the "Broedertrouw" inserter category. Field definitions are ACF Local JSON in [acf-json/](wp-content/themes/broedertrouw/acf-json/) — ACF's default save point for a child theme, so no filter is needed.
  - A block's field-group location rule must match the **registered** block name (`bt/hero`), not `acf/bt-hero`. Serialized block comments in post content must likewise read `<!-- wp:bt/hero ... -->`.
  - Never hand-edit `acf-json/*.json` with a generic JSON rewriter — re-serializing can drop the `fields` array. Edit fields in the ACF UI (it writes the JSON back) or re-import via `acf_import_field_group()`.
  - Keep exactly one copy of each field group: duplicate DB copies alongside the JSON ones break the block inspector.
- **Colors come from the Customizer, not CSS.** [includes/colors.php](wp-content/themes/broedertrouw/includes/colors.php) registers the brand palette via `blocksy:options:colors:palette:palettes` (same pattern as kingnature's `functions/blocksy/colors.php`); the theme's `:root` tokens in `style.css` resolve to `var(--theme-palette-color-N)` with hex fallbacks. Headings and the site title use palette color 4 (`#1A5C8A`, a blue-leaning navy at 7.1:1 on white), wired through the Customizer's "All Headings" option. Change a color in **General > Colors**; when you do, sync the fallback hex in `colors.php` and `style.css` so the two never disagree.
- **Every interactive element states both background and text color on hover.** Never fade a button with `opacity` — it drags the label toward the backdrop and destroys contrast. Text over a photo needs the overlay to guarantee the ratio, since the image can change. Audit with the contrast script pattern: compute WCAG ratios for normal *and* hover states across both languages, and only trust readings taken on elements that own their text (an `<img>` inherits `color` and produces false failures).
- **Sailing trips are a CPT** (`trip`, archive `/trips/`) with fields from the "Trip Details" group. Trips use the **classic editor** (`use_block_editor_for_post_type`) so the ACF fields are the default view; `show_in_rest` stays true for Polylang and the REST API. "Past" is derived from `date_end` at query time, never stored. Query trips through `bt_get_trips()` in [includes/trips.php](wp-content/themes/broedertrouw/includes/trips.php) so blocks, archives and related lists stay consistent; it deliberately passes no language argument because Polylang filters the query. Seed dev data with `wp eval-file wp-content/themes/broedertrouw/bin/seed-trips.php` (idempotent).
  - Date ranges render through `bt_trip_date_range()` in the CLDR short numeric format per locale — NL `dd-MM-y` with "t/m", DE `dd.MM.y` with "bis". The format strings and the range wording are translatable (with gettext contexts), so a language change never hardcodes separators. Within one month the shared month and year print once (`03 t/m 06-04-2027`); across a month or year boundary both dates print in full (`31-07-2026 t/m 09-08-2026`).
  - The field set mirrors what broedertrouw.de/.nl actually publish: dates, ports, booking mode, a berth price and an optional private-cabin price (both absolute, e.g. €265 / €420), highlight, includes/excludes, gallery. **There is no berth-availability data on the live sites** — don't reintroduce a status or capacity field without asking.
  - Every field carries a Polylang `translations` setting: `sync` for language-independent data (dates, ports, prices, gallery) and `translate` for text. Fields left unset default to "Ignore" and render a "This field is ignored" notice under every label.
  - Field labels are registered as Polylang strings in [includes/acf-labels.php](wp-content/themes/broedertrouw/includes/acf-labels.php), so wording is editable under **Languages > String translations** without a deploy.
- **Trip front-end output hangs off Blocksy hooks** (`blocksy:single:container:top` / `:bottom`, `blocksy:posts-listing:canvas:custom-output`) rather than template overrides, so Blocksy keeps owning the page shell. Verify hook names against the parent theme before adding new ones.
- **Languages:** Polylang Pro. NL is the default language (hidden from URLs), DE is secondary under `/de/`. Menus are assigned per language via Polylang's menu-location handling; widget blocks carry a `pll_lang` attribute so each sidebar widget renders only in its language.
- **Language switcher:** show ONLY the inactive language, as flag + code (Blocksy header language-switcher element with `hide_current_language`).
- **Design source:** Claude Design project "Broedertrouw website redesign" (`240887f9-6f33-462f-96a0-7eb07837b09d`) — one `*.dc.html` per page plus a CLAUDE.md with binding design rules (maritime blues, navy `#0F3A5C`, Archivo + Source Sans 3, informal Du/je tone, never em dashes in copy).

## Verifying UI changes

Verify rendered output on <https://broedertrouw.test> via the Playwright MCP ([.mcp.json](.mcp.json)) — navigate, screenshot, snapshot. The cert is self-signed; ignore HTTPS errors.

Front-end checks need no credentials. To inspect **wp-admin** (block editor, ACF field groups, trip list), copy [.env.local.example](.env.local.example) to `.env.local` and fill in `WP_ADMIN_USER` / `WP_ADMIN_PASS`; `.env.local` is gitignored. Prefer a throwaway admin account over personal credentials, and delete it when done. Note that opening a page in the editor takes a post lock — a second session gets the "already being edited" dialog and must take over.

## Commit conventions

Same as kingnature ([trafficflowgmbh/kingnature](https://github.com/trafficflowgmbh/kingnature)):

- Format: `[Prefix] Brief description in present tense` — prefixes `[Update]` (default), `[Fix]`, `[Add]`, `[Remove]`, `[Refactor]`, `[Cleanup]`, `[Docs]`.
- Subject under 72 chars, no trailing period. Multi-change commits get a `-` bullet body.
- **No Claude / AI co-author trailers.**
- Never commit directly to `master` — cut a feature branch (`add/<slug>`, `fix/<slug>`, `update/<slug>`) from latest `master`.
- Don't commit `wp-config.php`, `.env*`, or premium plugin code (public repo).
