/* jshint node:true */
module.exports = function(grunt) {
	'use strict';

	// Deploy files list.
	var deploy_files_list = [
		'images/**',
		'inc/**',
		'languages/**',
		'readme.txt',
		'<%= pkg.main_file %>'
	];

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		options: {
			text_domain: '<%= pkg.name %>'
		},

		// Generate POT file.
		makepot: {
			target: {
				options: {
					type: 'wp-plugin',
					domainPath: 'languages',
					exclude: ['deploy/.*','node_modules/.*'],
					updateTimestamp: false,
					potHeaders: {
						'x-poedit-keywordslist': true,
						'language-team': '',
						'Language': 'en_US',
						'X-Poedit-SearchPath-0': '../../<%= pkg.name %>',
						'plural-forms': 'nplurals=2; plural=(n != 1);',
					}
				}
			}
		},

		// Update text domain.
		addtextdomain: {
			options: {
				textdomain: '<%= options.text_domain %>',
				updateDomains: true
			},
			target: {
				files: {
					src: [
					'*.php',
					'**/*.php',
					'!node_modules/**',
					'!deploy/**',
					'!tests/**'
					]
				}
			}
		},

		// Copy files.
		copy: {
			deploy: {
				src: deploy_files_list,
				dest: 'deploy/<%= pkg.name %>',
				expand: true,
				dot: true
			}
		},

		// Clean the directory.
		clean: {
			deploy: ['deploy']
		}

	});

	// Load NPM tasks.
	grunt.loadNpmTasks('grunt-wp-i18n');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');

	// Register tasks.
	grunt.registerTask( 'default', [] );

	grunt.registerTask( 'build', [
		'addtextdomain',
		'makepot'
	]);

	grunt.registerTask( 'deploy', [
		'clean:deploy',
		'copy:deploy'
	]);
};
