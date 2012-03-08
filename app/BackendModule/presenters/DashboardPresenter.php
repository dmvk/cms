<?php

namespace BackendModule;

/**
 * @Secured(role="administrator")
 */
class DashboardPresenter extends BasePresenter
{

	public function actionDefault()
	{
		$this->template->logger = $this->context->logger->findAllOrderedByTimestamp();
	}

}
