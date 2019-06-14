<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 上午11:55
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Requests\Order;

use Dezsidog\CytSdk\Contracts\OutContract;

/**
 * Class ContactPerson
 * @package Deszidog\CytSdk\Requests\OrderCreate
 * @property    string  $name               联系人姓名
 * @property    string  $credentials        取票人证件
 * @property    string  $credentialsType    取票人证件类型 身份证 : ID_CARD 配合 credentials 生效
 */
class VisitPerson implements OutContract
{
    public $name;
    public $credentials;
    public $credentialsType = 'ID_CARD';

    public function __construct(string $name, string $credentials, ?string $credentialsType = null)
    {
        $this->name = $name;
        $this->credentials = $credentials;
        if ($credentialsType) {
            $this->credentialsType = $credentialsType;
        }
    }

    public function __toString(): string
    {
        $templet = <<<DOC
<qm:visitPerson>
    <qm:person>
        <qm:name>%s</qm:name>
        <qm:credentials>%s</qm:credentials>
        <qm:credentialsType>%s</qm:credentialsType>
    </qm:person>
</qm:visitPerson>
DOC;
        return sprintf(
            $templet,
            $this->name,
            $this->credentials,
            $this->credentialsType
        );

    }
}