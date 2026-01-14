// @ts-check
// Note: type annotations allow type checking and IDEs autocompletion

const lightCodeTheme = require('prism-react-renderer').themes.github;
const darkCodeTheme = require('prism-react-renderer').themes.dracula;

/** @type {import('@docusaurus/types').Config} */
const config = {
  title: 'MapBundle Documentation',
  tagline: 'Self-Hosted Maps with Protomaps (PMTiles)',
  favicon: 'img/favicon.ico',

  // Set the production url of your site here
  url: 'https://bikecoderslife.github.io',
  // Set the /<baseUrl>/ pathname under which your site is served
  baseUrl: '/MapBundle/',

  // GitHub pages deployment config.
  organizationName: 'BikeCodersLife',
  projectName: 'MapBundle',

  onBrokenLinks: 'warn',

  // Internationalization
  i18n: {
    defaultLocale: 'en',
    locales: ['en'],
  },

  // Mermaid diagram support
  markdown: {
    mermaid: true,
    hooks: {
      onBrokenMarkdownLinks: 'warn',
    },
  },
  themes: ['@docusaurus/theme-mermaid'],

  // Client modules for enhanced functionality
  clientModules: [
    require.resolve('./src/theme/mermaidZoom.js'),
  ],

  presets: [
    [
      'classic',
      /** @type {import('@docusaurus/preset-classic').Options} */
      ({
        docs: {
          sidebarPath: require.resolve('./sidebars.js'),
          editUrl: 'https://github.com/BikeCodersLife/MapBundle/tree/main/docs-site/',
          routeBasePath: '/',
        },
        blog: false,
        theme: {
          customCss: require.resolve('./src/css/custom.css'),
        },
      }),
    ],
  ],

  themeConfig:
    /** @type {import('@docusaurus/preset-classic').ThemeConfig} */
    ({
      image: 'img/mapbundle-social-card.jpg',
      navbar: {
        title: 'MapBundle',
        logo: {
          alt: 'MapBundle Logo',
          src: 'img/logo.svg',
        },
        items: [
          {
            type: 'docSidebar',
            sidebarId: 'docsSidebar',
            position: 'left',
            label: 'Documentation',
          },
          {
            href: 'https://docs.velogrid.com',
            label: 'VeloGrid Docs',
            position: 'right',
          },
          {
            href: 'https://github.com/BikeCodersLife/MapBundle',
            label: 'GitHub',
            position: 'right',
          },
        ],
      },
      footer: {
        style: 'dark',
        links: [
          {
            title: 'Docs',
            items: [
              {
                label: 'Getting Started',
                to: '/getting-started/installation',
              },
            ],
          },
          {
            title: 'BikeCoders Ecosystem',
            items: [
              {
                label: 'VeloGrid Platform',
                href: 'https://docs.velogrid.com',
              },
              {
                label: 'BikeCodersBundle',
                href: 'https://bikecoderslife.github.io/BikeCodersBundle/',
              },
            ],
          },
          {
            title: 'More',
            items: [
              {
                label: 'GitHub',
                href: 'https://github.com/BikeCodersLife/MapBundle',
              },
              {
                label: 'BikeCoders',
                href: 'https://bikecoders.life',
              },
            ],
          },
        ],
        copyright: `Copyright © ${new Date().getFullYear()} BikeCodersLife. Built with Docusaurus.`,
      },
      prism: {
        theme: lightCodeTheme,
        darkTheme: darkCodeTheme,
        additionalLanguages: ['php', 'bash', 'yaml', 'javascript'],
      },
      mermaid: {
        theme: {light: 'default', dark: 'dark'},
        options: {
          maxTextSize: 50000,
        },
      },
    }),
};

module.exports = config;
