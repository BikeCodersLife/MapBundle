/**
 * Creating a sidebar enables you to:
 - create an ordered group of docs
 - render a sidebar for each doc of that group
 - provide next/previous navigation

 The sidebars can be generated from the filesystem, or explicitly defined here.

 Create as many sidebars as you want.
 */

// @ts-check

/** @type {import('@docusaurus/plugin-content-docs').SidebarsConfig} */
const sidebars = {
  docsSidebar: [
    {
      type: 'doc',
      id: 'intro',
      label: '🏠 Introduction',
    },
    {
      type: 'category',
      label: '🚀 Getting Started',
      items: ['getting-started/installation', 'getting-started/configuration'],
    },
    {
      type: 'category',
      label: '📖 Guides',
      items: ['guides/self-hosted-maps', 'guides/protomaps', 'guides/map-download'],
    },
    {
      type: 'category',
      label: '🎨 Frontend',
      items: ['frontend/maplibre-integration'],
    },
    {
      type: 'category',
      label: '📚 Reference',
      items: ['reference/entities', 'reference/services'],
    },
  ],
};

module.exports = sidebars;
