# Panth WhatsApp Integration -- User Guide

This guide walks store administrators through installing, configuring,
and customising the Panth WhatsApp Integration extension.

---

## Table of contents

1. [Installation](#1-installation)
2. [Verifying the module is active](#2-verifying-the-module-is-active)
3. [Configuring the floating button](#3-configuring-the-floating-button)
4. [Configuring the product page button](#4-configuring-the-product-page-button)
5. [Configuring the category page banner](#5-configuring-the-category-page-banner)
6. [Advanced styling](#6-advanced-styling)
7. [Customising colours via theme-config.json](#7-customising-colours-via-theme-configjson)
8. [Troubleshooting](#8-troubleshooting)

---

## 1. Installation

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

> **Note:** Panth_Core is a required dependency and will be installed
> automatically by Composer.

---

## 2. Verifying the module is active

```bash
bin/magento module:status Panth_WhatsApp
# Module is enabled
```

You should also see **WhatsApp Integration** listed under the
**Panth Infotech** menu in the admin sidebar.

---

## 3. Configuring the floating button

Navigate to **Stores > Configuration > Panth Extensions > WhatsApp
Integration > Float Button Settings**.

1. Set **Enable WhatsApp Float Button** to **Yes**.
2. Enter your **WhatsApp Phone Number** with country code
   (e.g. `+1234567890`). Do not include dashes or spaces.
3. Optionally customise the **Default Message** -- this is pre-filled
   when the customer opens the WhatsApp chat.
4. Set the **Button Text** -- this appears as a tooltip on hover
   (desktop only; hidden on mobile).
5. Choose the **Button Position** -- Bottom Left, Bottom Right, Top
   Left, or Top Right.
6. Save the configuration and flush the cache.

The floating button appears on every page with a pulse animation on
first load. It automatically offsets from the back-to-top button to
avoid overlap.

---

## 4. Configuring the product page button

Navigate to **Stores > Configuration > Panth Extensions > WhatsApp
Integration > Product Page Settings**.

1. Set **Enable WhatsApp on Product Pages** to **Yes**.
2. Choose a **Button Text** (default: "Ask on WhatsApp").
3. Configure the **Message Template** using placeholders:
   - `{product_name}` -- replaced with the product name
   - `{product_url}` -- replaced with the product URL
4. Select a **Button Style**:
   - **Solid** -- filled background, best for primary CTAs
   - **Outline** -- border only, more subtle appearance
   - **Icon Only** -- compact circle with just the WhatsApp icon
   - **Text Only** -- plain link style, minimal footprint
5. Save and flush the cache.

The button appears in the product info actions area (next to
wishlist/compare on Hyva, below add-to-cart on Luma).

---

## 5. Configuring the category page banner

Navigate to **Stores > Configuration > Panth Extensions > WhatsApp
Integration > Category Page Settings**.

1. Set **Enable WhatsApp on Category Pages** to **Yes**.
2. Set the **Button Text** (default: "Chat with Us").
3. Configure the **Message Template** -- the current category name
   is automatically appended to the message.
4. Save and flush the cache.

A full-width assistance banner appears above the product listing on
category pages.

---

## 6. Advanced styling

Navigate to **Stores > Configuration > Panth Extensions > WhatsApp
Integration > Advanced Styling**.

Enter additional CSS classes (one per line) in the **Custom CSS
Classes** field. These classes are applied to all WhatsApp elements
(float button wrapper, product button, and category banner).

This is useful for adding Tailwind utility classes or custom CSS class
names defined in your theme.

---

## 7. Customising colours via theme-config.json

All WhatsApp button colours and sizes are controlled via CSS custom
properties. In a Hyva theme, override them in your child theme's
`theme-config.json`:

```json
{
  "whatsapp-button-bg": "#128C7E",
  "whatsapp-button-text": "#FFFFFF",
  "whatsapp-float-size": "64px"
}
```

Run `npm run build-tailwind` after changing the config to regenerate
the CSS.

Available variables:

| Variable | Default | Purpose |
|---|---|---|
| `--whatsapp-button-bg` | `#25D366` | Background colour |
| `--whatsapp-button-text` | `#FFFFFF` | Text / icon colour |
| `--whatsapp-float-size` | `56px` | Float button size (desktop) |
| `--whatsapp-float-size-mobile` | `56px` | Float button size (mobile) |
| `--whatsapp-float-icon-size` | `28px` | Icon size (desktop) |
| `--whatsapp-float-icon-size-mobile` | `28px` | Icon size (mobile) |
| `--whatsapp-float-side` | `24px` | Distance from screen edge |
| `--whatsapp-float-bottom-with-btt` | `92px` | Bottom offset |

---

## 8. Troubleshooting

| Symptom | Cause | Fix |
|---|---|---|
| Float button not visible | Module disabled or `enabled` set to No | Check `bin/magento module:status Panth_WhatsApp` and admin config |
| Button links to wrong number | Phone number format incorrect | Use full international format without spaces or dashes (e.g. `+1234567890`) |
| Product button not appearing | Product page button disabled | Enable in Product Page Settings |
| Category banner not appearing | Category page banner disabled | Enable in Category Page Settings |
| Colours don't match theme | CSS variables not overridden | Add overrides to your child theme's `theme-config.json` |
| Button overlaps back-to-top | Bottom offset too small | Increase `--whatsapp-float-bottom-with-btt` value |

---

## Support

For all questions, bug reports, or feature requests:

- **Email:** kishansavaliyakb@gmail.com
- **Website:** https://kishansavaliya.com
- **WhatsApp:** +91 84012 70422
