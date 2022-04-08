<?php

namespace App\Services\Company\Store;

use App\Models\Company;

class Storage
{
    /**
     * @param Company $company
     *
     * @return bool
     */
    public function setFileSystemWithCompany(Company $company): bool
    {
        $this->setConfigFileSystem($this->getDriver($company), $this->getRoot($company), $this->getUrl($company));

        return true;
    }

     /**
     * @param Company $company
     *
     * @return string
     */
    public function getDriver(Company $company): string
    {
        return 'local';
    }

      /**
     * @param Company $company
     *
     * @return string
     */
    public function getRoot(Company $company): string
    {
        return storage_path("app/public/company/{$company->id}");
    }

      /**
     * @param Company $company
     *
     * @return string
     */
    public function getUrl(Company $company): string
    {
        return "/storage/Company/{$company->id}";
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->setConfigFileSystem(
            config()->get('filesystems.disks.public.driver'),
            config()->get('filesystems.disks.public.root'),
            config()->get('filesystems.disks.public.url')
        );
    }

    /**
     * @param string $driver
     * @param string $root
     * @param string $url
     *
     * @return void
     */
    public function setConfigFileSystem(string $driver, string $root, string $url): void
    {
        config()->set('filesystems.disks.company.driver', $driver);
        config()->set('filesystems.disks.company.root', $root);
        config()->set('filesystems.disks.company.url', $url);
    }
}
