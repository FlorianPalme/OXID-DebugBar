[{$smarty.block.parent}]

[{oxscript include=$oViewConf->getModuleUrl('fpdebugbar','out/src/js/script.js')}]
[{fp_debug_bar}]

<div id="oxiddebugbar_wrapper" data-id="[{$debugbarProfileId}]"></div>