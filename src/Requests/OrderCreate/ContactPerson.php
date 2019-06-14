<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 上午11:51
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Requests\Order;

/**
 * Class ContactPerson
 * @package Deszidog\CytSdk\Requests\OrderCreate
 * @property    string  $name               取票人姓名
 * @property    string  $mobile             取票人电话
 * @property    string  $credentials        取票人证件
 * @property    string  $credentialsType    取票人证件类型 身份证 : ID_CARD,护照 : HUZHAO,台胞证 : TAIBAO 港澳通行证: GANGAO 其它：OTHER配合credentials 生效
 */
class ContactPerson extends VisitPerson
{
    public $mobile;

    public function __construct(string $name, string $credentials, string $mobile, string $credentialsType = 'ID_CARD')
    {
        parent::__construct($name, $credentials, $credentialsType);
        $this->mobile = $mobile;
    }

    public function __toString(): string
    {
        $templet = <<<DOC
<qm:contactPerson>
    <qm:name>%s</qm:name>
    <qm:credentials>%s</qm:credentials>
    <qm:credentialsType>%s</qm:credentialsType>
    <qm:mobile>%s</qm:mobile>
</qm:contactPerson>
DOC;
        return sprintf(
            $templet,
            $this->name,
            $this->credentials,
            $this->credentialsType,
            $this->mobile
        );

    }
}