<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleServiceContractNew\API\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

interface FeedInterface extends CustomAttributesDataInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const KEY_ID = 'id';
    const KEY_TITLE = 'title';
    const KEY_DESCRIPTION = 'description';
    const KEY_LINK = 'link';
    /**#@-*/

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getLink();
}
