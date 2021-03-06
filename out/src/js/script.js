var OXIDDebugBar = {
    $debugbar: null,
    $tabs: null,
    $contents: null,
    profileId: null,
    $wrapper: null,

    /**
     * Init der Debugbar
     */
    init: function(){
        var self = this;

        $(function(){
            self.$wrapper = $('#oxiddebugbar_wrapper');
            self.profileId = self.$wrapper.data('id');

            var baseUrl = sBaseUrl.replace(/&amp;/g, '&');

            $.ajax(baseUrl + 'cl=fpdebugbar_getprofile&profileid=' + self.profileId, {
                success: function (data) {
                    self.handleRequestSuccess(data, true);
                }
            });
        });
    },

    /**
     * Request der Init-Ajax-Funktion handeln
     */
    handleRequestSuccess: async function (data, addStyles) {
        var self = this;

        if (addStyles) {
            $(data.styles).each(function () {
                var $header;
                if( $('header').length )
                {
                    $header = $('header');
                }
                else if( $('#header').length )
                {
                    $header = $('#header');
                }

                if( $header )
                {
                    $($header).append(
                        '<link rel="stylesheet" type="text/css" href="' + this + '" />'
                    );
                }
            });

            await self.sleep(100);
        }

        self.$wrapper.append(data.profile);

        self.initProfile();
    },

    /**
     * Einfache Sleep-Funktion
     *
     * @returns {Promise<any>}
     */
    sleep: function(ms){
        return new Promise(resolve => setTimeout(resolve, ms));  
    },

    /**
     * Init des Profils
     */
    initProfile: function(){
        var self = this;

        self.$debugbar = $('#oxiddebugbar');
        self.$tabs = self.$debugbar.find('> .tabs > li:not(.close, .profile_select)');
        self.$contents = self.$debugbar.find('> .contents');

        self.initTabs();
        self.initContentTabs();
        self.initProfiler();
        self.initTranslationCalls();
    },

    /**
     * Profiler-Graph
     */
    initProfiler: function(){
        if (typeof fpDebugBarPerformanceProfilerData === 'undefined') return;

        Plotly.newPlot('fpDebugBarPerformanceProfiler', fpDebugBarPerformanceProfilerData, {
            showlegend: true,
            barmode: 'stack'
        }, {
            displayModeBar: false
        });
    },

    /**
     * Tabs / Content-Switch aktivieren
     */
    initTabs: function(){
        var self = this;

        self.$tabs.on('click', function () {
            var key = $(this).data('tab');

            self.$debugbar.addClass('open');

            // Tabs deaktivieren
            self.$tabs.removeClass('active');
            $(this).addClass('active');

            // Contents einblenden
            if (self.$contents.find(':visible').length === 0) {
                //console.log(self.$contents.find('[data-content="' + key + '"]'));
                // Nichts eingeblendet.. also sliden wir das erste ein
                self.$contents.find('[data-content="' + key + '"]').slideDown(400);
            } else {
                self.$contents.find('> .content').hide();
                self.$contents.find('[data-content="' + key + '"]').show();
            }
        });

        self.$debugbar.find('> .tabs > li.close').on('click', function(){
            self.$contents.find('> .content:visible').slideUp(400, function(){
                self.$tabs.removeClass('active');
            });
        });

        self.$debugbar.find('> .tabs > li.profile_select').on('change', 'select', function(){
            self.$wrapper = $('#oxiddebugbar_wrapper');
            self.$wrapper.html('');
            self.profileId = self.$wrapper.data('id');

            var baseUrl = sBaseUrl.replace(/&amp;/g, '&');

            $.ajax(baseUrl + 'cl=fpdebugbar_getprofile&profileid=' + $(this).val(), {
                success: function (data) {
                    self.handleRequestSuccess(data, false);
                }
            });
        });
    },


    /**
     * Handling der Content-Tabs
     */
    initContentTabs: function(){
        var self = this;

        self.$contents.find('.tabber').on('click', '> .tabs > li', function(){
            var $tab = $(this),
                $tabs = $tab.parent(),
                $tabber = $tabs.parent(),
                $contents = $tabber.find('> .contents'),
                $content = $contents.find('[data-content="' + $tab.data('tab') + '"]');

            $tabs.find('> li').removeClass('active');
            $tab.addClass('active');

            $contents.find('> .content').removeClass('active');
            $content.addClass('active');
        });
    },

    /**
     * Handling der Anzeige der Übersetzungs-Calls
     */
    initTranslationCalls: function(){
        var self = this;

        self.$contents.find('> .content.translations').on('click', '.view-calls', function(){
           $container = $(this).find('+ .calls-container');

           if ($container.is(':visible')) {
               $container.hide();
           } else {
               $container.show();
           }
        });
    }
};

OXIDDebugBar.init();