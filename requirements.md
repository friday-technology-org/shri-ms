# Remaining Requirements – Next Phase

## Overview
This document outlines the next phase of development for **LaraCMS** based on the gap analysis against core WordPress functionality and the outstanding UI/UX requests.

---

## 1. Core Feature Gaps (WordPress‑style)
- **User Management & Roles**
  - Implement authentication (Laravel Auth scaffolding) and role‑based permissions.
  - Admin UI for managing users, roles, and capabilities.
- **REST API**
  - Expose standard endpoints for posts, pages, CPTs, taxonomies, media, and comments (`/wp-json/wp/v2/...`).
- **Theme Customizer**
  - Provide a live‑preview API for theme settings (colors, fonts, layout options).
- **Widget API**
  - Full widget registration system and UI for assigning widgets to sidebars.
- **Shortcode Parser**
  - Add `[shortcode]` handling in post/content rendering.
- **Revisions / History**
  - Store post revisions and enable rollback.
- **Multisite Support**
  - Optional architecture for managing multiple sites from a single installation.
- **Pingbacks / Trackbacks**
  - Implement XML‑RPC handling for pingbacks.
- **oEmbed / Embeds**
  - Support embedding external content via oEmbed providers.

---

## 2. UI/UX Improvements (Outstanding Requests)
- **Theme Upload UI**
  - Align drag‑and‑drop theme upload layout with existing plugin upload UI.
  - Add top‑padding to the theme list view.
- **Uniform Spacing**
  - Ensure consistent padding/margin between category add page, add form, and list sections.
- **Menu Children**
  - Enable nested (child) menu items in the navigation builder.
- **Alert Colors**
  - Success alerts → green background.
  - Error alerts → red background.
- **Permalink Structure**
  - Change front‑end URLs to `slug/url` (e.g., `/about-us/url`) instead of just the slug.

---

## 3. Next‑Phase Action Items
| Category | Task | Priority | Owner | Status |
|---|---|---|---|---|
| **Auth** | Scaffold Laravel authentication & role system | High | Backend | To‑Do |
| **API** | Create API resources and routes for posts/CPTs | High | Backend | To‑Do |
| **Customizer** | Build a customizer service & Blade component | Medium | Frontend | To‑Do |
| **Widgets** | Develop widget registration & sidebar management | Medium | Frontend | To‑Do |
| **Shortcodes** | Implement shortcode parser middleware | Low | Backend | To‑Do |
| **Gutenberg** | Research integration options and prototype editor | Low | Research | To‑Do |
| **Admin UI** | Design admin dashboard layout and navigation | High | UI/UX | To‑Do |
| **Revisions** | Add `post_revisions` table and UI diff view | Medium | Backend | To‑Do |
| **Theme Upload** | Mirror plugin upload UI (drag‑and‑drop) and add top padding | High | Frontend | In‑Progress |
| **Spacing** | Refactor Blade templates for consistent margins | High | Frontend | To‑Do |
| **Menu Children** | Extend menu helper to support hierarchical items | Medium | Backend | To‑Do |
| **Alert Colors** | Update alert component CSS classes | Medium | Frontend | To‑Do |
| **Permalinks** | Adjust routing to generate `slug/url` format | High | Backend | To‑Do |

---

## 4. Verification Plan
- Write unit tests for new API endpoints and authentication flows.
- Manually test UI changes across browsers (Chrome/Firefox) for theme upload and alert colors.
- Verify permalink generation through functional tests (`php artisan test`).
- Conduct a code review of the `get_page_seo` helper usage on all post types.

---

*Document last updated: 2026‑07‑27*
