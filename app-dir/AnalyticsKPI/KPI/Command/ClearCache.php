<?php
namespace KPI\Command;

class ClearCache extends Command {
    function doExecute(\KPI\Controller\Request $request) {
        $cachePath = \KPI\Base\ApplicationConfig::getValue('cachePath');
        
        $fg = new \KPI\Base\FileGoodies();
        $fg->destroy($cachePath);
        
        $template = new \KPI\View\Template("KPI/View/clear_cache.php");
        $template->set("status","Cache was cleared");
        $this->invoke($template);
    }
}

?>
