<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Shoop\Shoop;

// use Eightfold\Shoop\FilterContracts\Interfaces\Associable;

// use Eightfold\Shoop\{
//     Helpers\Type,
//     Interfaces\Shooped,
//     Traits\ShoopedImp,
//     ESDictionary,
//     ESString,
//     ESArray,
//     ESBool
// };

// use Eightfold\ShoopShelf\ESPath;
// use Eightfold\ShoopShelf\ESUri;
// use Eightfold\ShoopShelf\Shoop;

/**
 * https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#/media/File:URI_syntax_diagram.svg
 *
 * ESScheme -˅- ESUrlAuthority ----------------------------------------------------^ ESUrlPath - ? - ESUrlQuery - # ESUrlFragment -->
 *           ˅- ESUrlUserInfo - : - ESUrlPassword - @ - ESUrlHost - : - ESUrlPort -^
 */
class ESUrlAuthority extends ESUri
{
    public function host(): ESString
    {
        return $this->main();
    }

    public function userInfo(): ESUrlUserInfo
    {
        return $this->host()->userInfo();
    }

    public function password(): ESUrlPassword
    {
        return $this->host()->password();
    }

    public function port(): ESUrlPort
    {
        return $this->host()->port();
    }
}
