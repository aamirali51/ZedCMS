<!DOCTYPE html>
<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>CMS Settings Card</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700;800&amp;family=Noto+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              "primary": "#4f46e5","primary-hover": "#4338ca","success": "#10b981","surface-border": "#e5e7eb","text-main": "#111827","text-secondary": "#6b7280",},
            fontFamily: {
              "display": ["Space Grotesk", "sans-serif"],
              "body": ["Noto Sans", "sans-serif"],
            },
            boxShadow: {
                "card": "0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)",
            }
          },
        },
      }
    </script>
<style>
        .toggle-checkbox:checked {
            right: 0;
            border-color: #10b981;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #10b981;
        }
        .toggle-checkbox {
            appearance: none;
            -webkit-appearance: none;
            position: absolute;
            z-index: 10;
            border-radius: 9999px;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .toggle-label {
            width: 44px;
            height: 24px;
            position: relative;
            display: inline-block;
            border-radius: 9999px;
            background-color: #e5e7eb;
            transition: background-color 0.2s ease-in;
            cursor: pointer;
        }
        .toggle-label:after {
            content: "";
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #fff;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: transform 0.2s cubic-bezier(0.4, 0.0, 0.2, 1);
        }
        .toggle-checkbox:checked + .toggle-label:after {
            transform: translateX(20px);
        }
    </style>
</head>
<body class="font-body text-slate-800 bg-gray-50 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-3xl">
<div class="mb-6 flex items-center gap-2 text-sm text-text-secondary">
<span class="material-symbols-outlined text-[18px]">settings</span>
<span>Settings</span>
<span class="material-symbols-outlined text-[14px]">chevron_right</span>
<span class="text-text-main font-medium">SEO &amp; Performance</span>
</div>
<div class="bg-white rounded-xl shadow-card border border-surface-border overflow-hidden">
<div class="px-6 py-5 border-b border-surface-border flex items-center justify-between bg-white">
<div>
<h2 class="text-xl font-bold text-text-main font-display">SEO Configuration</h2>
<p class="text-sm text-text-secondary mt-1">Manage search engine visibility and caching strategies.</p>
</div>
<button class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow flex items-center gap-2 group">
<span class="material-symbols-outlined text-[18px] group-hover:scale-110 transition-transform">save</span>
                    Save Changes
                </button>
</div>
<div class="p-0">
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-6 md:p-8 border-b border-surface-border items-start hover:bg-gray-50/50 transition-colors">
<div class="md:col-span-4">
<label class="block text-sm font-semibold text-text-main mb-1" for="site_title">Site Title</label>
<p class="text-xs text-text-secondary">Appears in the browser tab and search results.</p>
</div>
<div class="md:col-span-8">
<div class="relative group">
<input class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-11 transition-colors pl-10" id="site_title" name="site_title" type="text" value="Zed CMS - Ultimate Content Platform"/>
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
<span class="material-symbols-outlined text-[20px]">title</span>
</div>
</div>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-6 md:p-8 border-b border-surface-border items-center hover:bg-gray-50/50 transition-colors">
<div class="md:col-span-4">
<label class="block text-sm font-semibold text-text-main mb-1">Allow Search Indexing</label>
<p class="text-xs text-text-secondary">If disabled, adds 'noindex' meta tag to headers.</p>
</div>
<div class="md:col-span-8 flex items-center justify-between sm:justify-start sm:gap-4">
<div class="relative inline-block w-11 h-6 align-middle select-none">
<input checked="" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" id="toggle" name="toggle" type="checkbox"/>
<label class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer" for="toggle"></label>
</div>
<span class="text-sm font-medium text-success ml-2 flex items-center gap-1">
<span class="material-symbols-outlined text-[16px]">check_circle</span>
                            Active
                        </span>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-6 md:p-8 items-start hover:bg-gray-50/50 transition-colors">
<div class="md:col-span-4">
<label class="block text-sm font-semibold text-text-main mb-1" for="caching_engine">Caching Engine</label>
<p class="text-xs text-text-secondary">Select the driver for content caching.</p>
</div>
<div class="md:col-span-8">
<div class="relative">
<select class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm h-11 pl-10 pr-10 appearance-none bg-white" id="caching_engine" name="caching_engine">
<option>Redis (Recommended)</option>
<option>Memcached</option>
<option>File System</option>
<option>None</option>
</select>
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
<span class="material-symbols-outlined text-[20px]">memory</span>
</div>
<div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
<span class="material-symbols-outlined text-[20px]">expand_more</span>
</div>
</div>
<div class="mt-3 flex items-start gap-2 p-3 bg-blue-50 text-blue-700 rounded-md text-xs border border-blue-100">
<span class="material-symbols-outlined text-[16px] mt-0.5">info</span>
<p>Changing the caching engine requires a restart of the worker process.</p>
</div>
</div>
</div>
</div>
<div class="bg-gray-50 px-6 py-4 border-t border-surface-border flex justify-between items-center text-xs text-text-secondary">
<span>Last updated: 2 mins ago</span>
<a class="text-primary hover:text-primary-hover font-medium flex items-center gap-1" href="#">
                    View Logs
                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
</a>
</div>
</div>
<div class="mt-8 text-center">
<p class="text-sm text-gray-400">Zed CMS v2.4.0 © 2023</p>
</div>
</div>

</body></html>