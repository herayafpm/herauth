<?php
/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the frameworks
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter4.github.io/CodeIgniter4/
 */

use Raydragneel\Herauth\Config\Services;

if (! function_exists('herauth_helper')) {
    /**
     * Loads a helper file into memory. Supports namespaced helpers,
     * both in and out of the 'helpers' directory of a namespaced directory.
     *
     * Will load ALL helpers of the matching name, in the following order:
     *   1. app/Helpers
     *   2. {namespace}/Helpers
     *   3. system/Helpers
     *
     * @param array|string $filenames
     *
     * @throws FileNotFoundException
     */
    function herauth_helper($filenames)
    {
        static $loaded = [];

        $loader = Services::locator();

        if (! is_array($filenames)) {
            $filenames = [$filenames];
        }

        // Store a list of all files to include...
        $includes = [];

        foreach ($filenames as $filename) {
            // Store our system and application helper
            // versions so that we can control the load ordering.
            $systemHelper  = null;
            $appHelper     = null;
            $localIncludes = [];

            if (strpos($filename, '_helper') === false) {
                $filename .= '_helper';
            }

            // Check if this helper has already been loaded
            if (in_array($filename, $loaded, true)) {
                continue;
            }

            // If the file is namespaced, we'll just grab that
            // file and not search for any others
            if (strpos($filename, '\\') !== false) {
                $path = $loader->locateFile($filename, 'Helpers');

                if (empty($path)) {
                    throw FileNotFoundException::forFileNotFound($filename);
                }

                $includes[] = $path;
                $loaded[]   = $filename;
            } else {
                // No namespaces, so search in all available locations
                $paths = $loader->search('Helpers/' . $filename);

                foreach ($paths as $path) {
                    if (strpos($path, APPPATH . 'Helpers' . DIRECTORY_SEPARATOR) === 0) {
                        $appHelper = $path;
                    } elseif (strpos($path, SYSTEMPATH . 'Helpers' . DIRECTORY_SEPARATOR) === 0) {
                        $systemHelper = $path;
                    } else {
                        $localIncludes[] = $path;
                        $loaded[]        = $filename;
                    }
                }

                // App-level helpers should override all others
                if (! empty($appHelper)) {
                    $includes[] = $appHelper;
                    $loaded[]   = $filename;
                }

                // All namespaced files get added in next
                $includes = array_merge($includes, $localIncludes);

                // And the system default one should be added in last.
                if (! empty($systemHelper)) {
                    $includes[] = $systemHelper;
                    $loaded[]   = $filename;
                }
            }
        }

        // Now actually include all of the files
        foreach ($includes as $path) {
            include_once $path;
        }
    }
}