# Changelog

All notable changes to this extension are documented here. The format
is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.1.0] -- Marketplace preparation

### Changed
- Composer vendor changed from `panth/` to `mage2kishan/` for Adobe
  Marketplace publishing.
- PHP version constraint updated to `~8.1.0||~8.2.0||~8.3.0||~8.4.0`
  (dropped PHP 7.4 support).
- Added marketplace assets: README.md, USER_GUIDE.md, CHANGELOG.md,
  LICENSE.txt, COPYING.txt, .gitattributes.

### Fixed
- All MEQP (Magento Extension Quality Program) code-sniffer errors
  resolved -- zero errors at severity 10.

---

## [1.0.0] -- Initial release

### Added
- **Floating WhatsApp button** on all pages with configurable position
  (bottom-left, bottom-right, top-left, top-right), pulse animation,
  and hover tooltip.
- **Product page WhatsApp button** with four styles: solid, outline,
  icon-only, and text-only. Supports `{product_name}` and
  `{product_url}` placeholders in the message template.
- **Category page assistance banner** with WhatsApp contact button and
  automatic category name appending.
- **Admin configuration** under Stores > Configuration > Panth
  Extensions > WhatsApp Integration with per-store-view settings.
- **CSS variable theming** via `theme-config.json` for seamless Hyva
  design token integration.
- **Custom CSS classes** field for additional Tailwind or custom
  styling from the admin panel.
- Hyva and Luma theme compatibility.

### Compatibility
- Magento Open Source / Commerce / Cloud 2.4.4 -- 2.4.8
- PHP 8.1, 8.2, 8.3, 8.4

---

## Support

For all questions, bug reports, or feature requests:

- **Email:** kishansavaliyakb@gmail.com
- **Website:** https://kishansavaliya.com
- **WhatsApp:** +91 84012 70422
