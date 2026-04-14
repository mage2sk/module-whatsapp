# Panth WhatsApp Integration

[![Magento 2.4.4 - 2.4.8](https://img.shields.io/badge/Magento-2.4.4%20--%202.4.8-orange)]()
[![PHP 8.1 - 8.4](https://img.shields.io/badge/PHP-8.1%20--%208.4-blue)]()
[![Hyva & Luma](https://img.shields.io/badge/Theme-Hyva%20%26%20Luma-purple)]()

Add a **WhatsApp contact button** to your Magento 2 storefront in three
places: a floating chat button on every page, an inquiry button on
product detail pages, and an assistance banner on category listing
pages. Works with both Hyva and Luma themes.

---

## Features

- **Floating WhatsApp button** — appears on all pages with configurable
  position (bottom-left, bottom-right, top-left, top-right), pulse
  animation on load, and hover tooltip.
- **Product page button** — four button styles (solid, outline,
  icon-only, text-only) with automatic product name and URL
  placeholders in the pre-filled WhatsApp message.
- **Category page banner** — full-width assistance banner above the
  product listing with the current category name appended to the
  message.
- **CSS variable theming** — all colours and sizes are controlled via
  CSS variables defined in `theme-config.json`, so they integrate
  seamlessly with Hyva's design token system.
- **Admin configuration** — full control from
  `Stores > Configuration > Panth Extensions > WhatsApp Integration`
  with per-store-view settings.
- **Custom CSS classes** — inject additional Tailwind or custom CSS
  classes from the admin panel for fine-tuned styling.

---

## Installation

### Composer (recommended)

```bash
composer require mage2kishan/module-whatsapp
bin/magento module:enable Panth_WhatsApp
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

### Manual zip

1. Download the extension package zip
2. Extract to `app/code/Panth/WhatsApp`
3. Run the same `module:enable ... cache:flush` commands above

> **Dependency:** This module requires `Panth_Core` (mage2kishan/module-core).
> Composer installs it automatically.

---

## Requirements

| | Required |
|---|---|
| Magento | 2.4.4 -- 2.4.8 (Open Source / Commerce / Cloud) |
| PHP | 8.1 / 8.2 / 8.3 / 8.4 |
| Panth_Core | ^1.0 |

---

## Configuration

Navigate to **Stores > Configuration > Panth Extensions > WhatsApp
Integration**.

### Float Button Settings

| Setting | Default | Description |
|---|---|---|
| Enable WhatsApp Float Button | No | Show/hide the floating button |
| WhatsApp Phone Number | — | Phone number with country code (e.g. +1234567890) |
| Default Message | Hi! I have a question about your products. | Pre-filled message on click |
| Button Text | Chat with Us | Tooltip text on hover |
| Button Position | Bottom Left | Corner of the screen |

### Product Page Settings

| Setting | Default | Description |
|---|---|---|
| Enable on Product Pages | No | Show/hide the product inquiry button |
| Button Text | Ask on WhatsApp | Button label |
| Message Template | Hi! I'm interested in {product_name}. {product_url} | Supports `{product_name}` and `{product_url}` placeholders |
| Button Style | Solid | Solid, Outline, Icon Only, or Text Only |

### Category Page Settings

| Setting | Default | Description |
|---|---|---|
| Enable on Category Pages | No | Show/hide the category assistance banner |
| Button Text | Chat with Us | Banner button label |
| Message Template | Hi! I need help finding products in your store. | Pre-filled message with category name appended |

### Advanced Styling

| Setting | Description |
|---|---|
| Custom CSS Classes | Additional CSS classes applied to all WhatsApp elements (one per line) |

---

## CSS Variables (theme-config.json)

| Variable | Default | Purpose |
|---|---|---|
| `--whatsapp-button-bg` | `#25D366` | Button background colour |
| `--whatsapp-button-text` | `#FFFFFF` | Button text / icon colour |
| `--whatsapp-float-size` | `56px` | Float button diameter (desktop) |
| `--whatsapp-float-size-mobile` | `56px` | Float button diameter (mobile) |
| `--whatsapp-float-icon-size` | `28px` | Float icon size (desktop) |
| `--whatsapp-float-icon-size-mobile` | `28px` | Float icon size (mobile) |
| `--whatsapp-float-side` | `24px` | Distance from screen edge |
| `--whatsapp-float-bottom-with-btt` | `92px` | Bottom offset (avoids back-to-top overlap) |

---

## Support

| Channel | Contact |
|---|---|
| Email | kishansavaliyakb@gmail.com |
| Website | https://kishansavaliya.com |
| WhatsApp | +91 84012 70422 |

---

## License

Proprietary -- see `LICENSE.txt`. One license per production domain.

---

## About the developer

Built and maintained by **Kishan Savaliya** -- https://kishansavaliya.com.
High-quality, security-focused Magento 2 extensions and themes for both
Hyva and Luma storefronts.
