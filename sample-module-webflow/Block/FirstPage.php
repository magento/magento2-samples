<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleWebFlow\Block;

class FirstPage extends \Magento\Framework\View\Element\Template
{
    /**
     * Returns URL linking to the next page.
     *
     * @return string
     */
    public function getNextPageUrl()
    {
        /**
         * The layout file, webflow_firstpage_index.xml, provides an argument to this block. The argument contains
         * the path to the next page: "webflow/nextpage". Because the argument is of type url, the application converts
         * that path into a fully qualified URL based on the store's base url. This string is then passed into the
         * block via the $data array, and it is retrieved via the getData method.
         */
        return $this->getData('url');
    }
}