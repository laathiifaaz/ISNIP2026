// tailwind.config.js
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          primary:  '#1E3A5F',       // Navy dark - sidebar bg, banner
          mid:      '#1D4E8F',       // Blue mid - active buttons, Create Job
          light:    '#2D6BC4',       // Blue accent - hover, active sidebar link
          subtle:   '#EEF4FF',       // Blue subtle - sidebar active item highlight
        },
        surface: {
          page:     '#F0F4F8',       // Main page bg (light blue-grey)
          card:     '#FFFFFF',       // Cards, forms, tables bg
          sidebar:  '#1E3A5F',       // Sidebar bg
          header:   '#FFFFFF',       // Header bg
          input:    '#FFFFFF',       // Input field bg
          'input-border': '#D1D9E0', // Input border color
          'table-header': '#F8FAFC', // Table header row bg
          'table-row-hover': '#F0F4F8', // Table row hover bg
        },
        text: {
          primary:  '#1A2332',       // Heading text
          secondary:'#4A5568',       // Body text, labels
          muted:    '#718096',       // Placeholders, helpers
          'on-dark': '#FFFFFF',      // Text on dark backgrounds
          'on-dark-muted': '#A0AEC0',// Subtitle text on dark backgrounds
          link:     '#1D4E8F',       // Link text
          'link-hover': '#2D6BC4',   // Link hover text
        },
        status: {
          running:   '#3182CE',      // Running blue
          'running-bg': '#EBF8FF',   // Running badge bg
          'running-text': '#1D4E8F', // Running badge text
          
          finished:  '#38A169',      // Finished green
          'finished-bg': '#F0FFF4',  // Finished badge bg
          'finished-text': '#276749',// Finished badge text
          
          submitted: '#D69E2E',      // Submitted yellow
          'submitted-bg': '#FFFBEB', // Submitted badge bg
          'submitted-text': '#744210',// Submitted badge text
          
          canceled:  '#E53E3E',      // Canceled red
          'canceled-bg': '#FFF5F5',  // Canceled badge bg
          'canceled-text': '#742A2A',// Canceled badge text
          
          failed:    '#E53E3E',      // Failed red
          'failed-bg': '#FFF5F5',    // Failed badge bg
          'failed-text': '#742A2A',  // Failed badge text
        },
        step: {
          'completed-bg': '#1D4E8F', // Completed step circle bg
          'completed-text': '#FFFFFF',// Completed step checkmark text
          'pending-bg': '#E2E8F0',   // Pending step circle bg
          'pending-text': '#718096',  // Pending step number text
          'badge-completed': '#EBF8FF',// Completed badge bg
          'badge-completed-text': '#1D4E8F', // Completed badge text
        },
        semantic: {
          error:    '#E53E3E',
          'error-bg': '#FFF5F5',
          'error-border': '#FEB2B2',
          success:  '#38A169',
          'success-bg': '#F0FFF4',
          warning:  '#D69E2E',
          info:     '#3182CE',
        },
        'stat-card': {
          'analyses-bg': '#EBF8FF',  // Total Analyses bg
          'running-bg': '#FFF8E1',   // Currently Running bg
          'storage-bg': '#F0FFF4',   // Storage Used bg
          'runtime-bg': '#F5F0FF',   // Average Runtime bg
        }
      },
      fontFamily: {
        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
        mono: ['JetBrains Mono', 'Fira Code', 'Courier New', 'monospace'],
      },
      fontSize: {
        'xs':   '11px',
        'sm':   '12px',
        'base': '13px',
        'md':   '14px',
        'lg':   '16px',
        'xl':   '18px',
        '2xl':  '22px',
        '3xl':  '28px',
        '4xl':  '36px',
      },
      borderRadius: {
        'sm': '4px',
        'md': '6px',
        'lg': '8px',
        'xl': '12px',
      },
      width: {
        'sidebar': '220px',
        'sidebar-sm': '60px',
      },
      height: {
        'header': '56px',
      },
    },
  },
  plugins: [],
}
