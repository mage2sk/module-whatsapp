# Changelog

All notable changes to this extension are documented here. The format
is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.0.15] - 2026-10-03

### Fixed
- On phones the floating button could sit on top of the layered navigation "Shop By" toggle (for example right after applying a filter), so the toggle could not be tapped. The button now fades out while it would cover the filter toggle, filter titles, active filter remove links, "Clear All" or the toolbar sorter, pager and view mode links, and comes back once those controls scroll clear. Any element with a `data-panth-float-avoid` attribute is kept clear the same way. The button also stays hidden while the mobile filter panel is open (Hyva below 768px and the Luma filter overlay).
