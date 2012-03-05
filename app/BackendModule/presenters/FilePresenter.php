<?php

namespace BackendModule;

use Nette\Utils\Finder;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;

/**
 * @Secured(role="admin")
 * 
 * @todo Dodelat + Refractoring :P
 */
class FilePresenter extends BasePresenter
{

	public function renderDefault($path)
	{
		if ($this->isAjax()) {
			$this->invalidateControl("browser");
		}

		$path = $this->sanitizePath($path);

		$this["directoryForm"]->setPath($this->getFullPath($path));

		$this->template->files = Finder::find("*")->in($this->getFullPath($path));
		$this->template->breadcrumbs = $this->getBreadcrumbs($path);
		$this->template->path = $this->sanitizePath($path);
	}

	public function getFullPath($path = NULL)
	{
		return WWW_DIR . "/media/" . $path;
	}

	public function getBreadcrumbs($path)
	{
		$breadcrumbs = array("" => "Media");

		$pieces = explode("/", $path);

		$path = "";

		foreach ($pieces as $piece) {
			$path .= $piece . "/";
			$breadcrumbs[$path] = $piece;
		}

		return $breadcrumbs;
	}

	public function handleDelete($path)
	{
		$this->remove($this->getFullPath($path));
		$this->flashMessage("Soubor/Adresář byl úspěšně smazán!", "success");

		if (!$this->isAjax()) {
			$this->redirect("default");
		}
	}

	private function remove($path)
	{
		if (is_file($path)) {
			unlink($path);
		} elseif (is_dir($path)) {
			$objects = scandir($path);

			if (is_array($objects)) {
				foreach ($objects as $object) {
					if ($object !== "." && $object !== "..") {
						$this->remove($path . "/" . $object);
					}
				}
			}

			rmdir($path);
		} else {
			throw new \Nette\InvalidArgumentException("There is no file or directory at given path");
		}
	}

	private function sanitizePath($path)
	{
		$path = str_replace("\\", "/", $path);
		$path = str_replace("\/\/", "/", $path);
		$path = str_replace("..", "", $path);

		return trim($path, "/");
	}

	public function createComponentDirectoryForm()
	{
		return new DirectoryForm();
	}

	public function createComponentUploadForm()
	{
		return new UploadForm();
	}

}