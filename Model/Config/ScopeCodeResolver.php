<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ScopeCodeResolver extends \Magento\Framework\App\Config\ScopeCodeResolver
{
    /**
     * @var \Magento\Framework\App\ScopeResolverPool
     */
    private $scopeResolverPool;

    /**
     * @var array
     */
    private $resolvedScopeCodes = [];

    /**
     * @param \Magento\Framework\App\ScopeResolverPool $scopeResolverPool
     */
    public function __construct(
        \Magento\Framework\App\ScopeResolverPool $scopeResolverPool
    ) {
        $this->scopeResolverPool = $scopeResolverPool;
    }

    /**
     * Resolve scope code
     *
     * @param string $scopeType
     * @param string $scopeCode
     * @return string
     */
    public function resolve($scopeType, $scopeCode)
    {
        if (isset($this->resolvedScopeCodes[$scopeType][$scopeCode])) {
            return $this->resolvedScopeCodes[$scopeType][$scopeCode];
        }
        if (($scopeCode === null || is_numeric($scopeCode))
            && $scopeType !== ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        ) {
            $scopeResolver = $this->scopeResolverPool->get($scopeType);
            $resolverScopeCode = $scopeResolver->getScope($scopeCode);
        } else {
            $resolverScopeCode = $scopeCode;
        }

        if ($resolverScopeCode instanceof \Magento\Framework\App\ScopeInterface) {
            $resolverScopeCode = $resolverScopeCode->getCode();
        }

        $this->resolvedScopeCodes[$scopeType][$scopeCode] = $resolverScopeCode;
        return $resolverScopeCode;
    }

    /**
     * @return void
     */
    public function reset()
    {
        $this->resolvedScopeCodes = [];
    }
}
