import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import plugin from 'tailwindcss/plugin';
const plugin = require("tailwindcss/plugin")
const tokens = require("./resources/design-tokens.json")
const _ = require("lodash")

function getValue(origin, val) {
	if (val && val.indexOf("{") === 0) {
		const path = val.replace("{", "").replace("}", "")
		return _.get(origin, path)
	}

	return val
}

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        "./node_modules/flowbite/**/*.js",
        "./index.html",
        "./src/**/*.{vue,js,ts,jsx,tsx}",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    'Inter',
                    'ui-sans-serif',
                    'system-ui',
                    '-apple-system',
                    'BlinkMacSystemFont',
                    '"Segoe UI"',
                    'Roboto',
                    '"Helvetica Neue"',
                    'Arial',
                    '"Noto Sans"',
                    'sans-serif',
                    '"Apple Color Emoji"',
                    '"Segoe UI Emoji"',
                    '"Segoe UI Symbol"',
                    '"Noto Color Emoji"',
                ],
                serif: [
                    'ui-serif',
                    'Georgia',
                    'Cambria',
                    '"Times New Roman"',
                    'Times',
                    'serif',
                ],
                mono: [
                    'ui-monospace',
                    'SFMono-Regular',
                    'Menlo',
                    'Monaco',
                    'Consolas',
                    '"Liberation Mono"',
                    '"Courier New"',
                    'monospace',
                ],
            },
            colors: {

                "main-content-colour": "#f0f0f5",

                "card-first"  : "#6c5ffc",
                "card-second" : "#05c3fb",
                "card-third"  : "#09ad95",
                "card-fourth" : "#1170e4",

                "success-button" : "#13bfa6",
                "success-button-hover" : "#65d5c5",
                "danger-button" : "#e82646",
                "danger-button-hover" : "#f07287",
                "secondary-button" : "#05c3fb",
                "secondary-button-hover" : "#5cd8fc",
                "primary-button" : "#6c5ffc",
                "primary-button-hover" : "#9f97fd",

                "need-approval-button" : "#2e20cb",
                "need-approval-button-hover" : "#584dd5",

                "side-bar-colour" : "#f7fafc",
                "side-bar-colour-hover" :"#edf2f7",
                "side-bar-text-colour" : "#8f9dab",

                primary: "#DFA04B",
                secondary: "#67c148",
        
                "pgreen-800": "#56811f",
        
                "pblue-800": "#05668d",
        
                "pyellow-800": "#dfa04b",
        
                "pOrange-800": "#cc503e",

                "AddButton-color-800" : "#05668d",
                "AddButton-color-900" : "#0a5070",

                "primary-text-blue" : "#2c7be5",

                "primary-text" : "#94a3b8",
                "primary-colour" : "#f7fafc",
                "primary-50" : "#edf2f9",
                "primary-100" : "#e4ebf5",
                "primary-200" : "#cfdcee",
                "primary-300" : "#aec6e3",
                "primary-400" : "#87a7d3",
                "primary-500" : "#6a8bc7",
                "primary-600" : "#5773ba",
                "primary-700" : "#4c62a9",
                "primary-800" : "#42518b",
                "primary-900" : "#39466f",
                "primary-950" : "#262c45",
              },
        },
    },

    plugins: [
        forms,
        require('flowbite/plugin')({
            charts: true,
        }),
        plugin(function ({ addBase, theme }) {
			addBase({
				h1: {
					fontFamily: getValue(tokens, tokens?.typography?.h1?.fontFamily),
					fontWeight: getValue(tokens, tokens?.typography?.h1?.fontWeight) || theme("fontWeight.bold"),
					fontSize: getValue(tokens, tokens?.typography?.h1?.fontSize),
					letterSpacing:
						getValue(tokens, tokens?.typography?.h1?.letterSpacing) || theme("letterSpacing.tight")
				},
				h2: {
					fontFamily: getValue(tokens, tokens?.typography?.h2?.fontFamily),
					fontWeight: getValue(tokens, tokens?.typography?.h2?.fontWeight) || theme("fontWeight.bold"),
					fontSize: getValue(tokens, tokens?.typography?.h2?.fontSize),
					letterSpacing:
						getValue(tokens, tokens?.typography?.h2?.letterSpacing) || theme("letterSpacing.tight")
				},
				h3: {
					fontFamily: getValue(tokens, tokens?.typography?.h3?.fontFamily),
					fontWeight: getValue(tokens, tokens?.typography?.h3?.fontWeight) || theme("fontWeight.bold"),
					fontSize: getValue(tokens, tokens?.typography?.h3?.fontSize),
					letterSpacing:
						getValue(tokens, tokens?.typography?.h3?.letterSpacing) || theme("letterSpacing.tight")
				},
				h4: {
					fontFamily: getValue(tokens, tokens?.typography?.h4?.fontFamily),
					fontWeight: getValue(tokens, tokens?.typography?.h4?.fontWeight) || theme("fontWeight.medium"),
					fontSize: getValue(tokens, tokens?.typography?.h4?.fontSize),
					letterSpacing:
						getValue(tokens, tokens?.typography?.h4?.letterSpacing) || theme("letterSpacing.tight")
				},
				h5: {
					fontFamily: getValue(tokens, tokens?.typography?.h5?.fontFamily),
					fontWeight: getValue(tokens, tokens?.typography?.h5?.fontWeight) || theme("fontWeight.bold"),
					fontSize: getValue(tokens, tokens?.typography?.h5?.fontSize),
					letterSpacing:
						getValue(tokens, tokens?.typography?.h5?.letterSpacing) || theme("letterSpacing.tight")
				},
				h6: {
					fontFamily: getValue(tokens, tokens?.typography?.h6?.fontFamily),
					fontWeight: getValue(tokens, tokens?.typography?.h6?.fontWeight) || theme("fontWeight.bold"),
					fontSize: getValue(tokens, tokens?.typography?.h6?.fontSize),
					letterSpacing: getValue(tokens, tokens?.typography?.h6?.letterSpacing)
				}
			})
		})
    ],
};
