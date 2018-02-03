module.exports = function(grunt) {

    grunt.initConfig({
        watch: {
            sass: {
                files: ['out/src/scss/*.scss'],
                tasks: ['sass'],
                options: {
                    interrupt: true
                }
            }
        },

        sass: {
            options: {
                sourceMap: true
            },

            default: {
                files: [{
                    expand: true,
                    cwd: 'out/src/scss/',
                    src: ['*.scss'],
                    dest: 'out/src/css',
                    ext: '.css',
                    extDot: 'last'
                }]
            }
        }
    });

    require('load-grunt-tasks')(grunt);

    grunt.registerTask('dev', ['watch:sass']);

};