<?php

namespace Amirrajabzadeh\FarapayamakLaravel\Services;

use SoapFault;

/**
 * وب سرویس دفترچه تلفن فراپیامک (Contacts.asmx)
 *
 * این کلاس شامل متدهای مربوط به مدیریت گروه‌ها و
 * مخاطبین دفترچه تلفن می‌باشد
 *
 * @package Farapayamak\Laravel\Services
 */
class ContactsService extends BaseService
{
    /**
     * دریافت لیست گروه‌ها
     *
     * @return array
     */
    public function getGroups()
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password
            ];

            $result = $this->client->GetGroups($params);
            return $result->GetGroupsResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * اضافه کردن گروه جدید
     *
     * @param string $groupName نام گروه
     * @param string $description توضیحات
     * @param bool $showToChilds نمایش به زیرمجموعه
     * @return int شناسه گروه ایجاد شده
     */
    public function addGroup($groupName, $description = '', $showToChilds = false)
    {
        try {
            $params = [
                'username'     => $this->username,
                'password'     => $this->password,
                'groupName'    => $groupName,
                'descriptions' => $description,
                'showToChilds' => $showToChilds
            ];

            $result = $this->client->AddGroup($params);
            return $result->AddGroupResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ویرایش گروه
     *
     * @param int $groupId شناسه گروه
     * @param string $groupName نام جدید گروه
     * @param string $description توضیحات جدید
     * @param bool $showToChilds نمایش به زیرمجموعه
     * @param bool $groupStatus وضعیت گروه
     * @return bool
     */
    public function changeGroup($groupId, $groupName, $description = '', $showToChilds = false, $groupStatus = true)
    {
        try {
            $params = [
                'username'     => $this->username,
                'password'     => $this->password,
                'groupId'      => $groupId,
                'groupName'    => $groupName,
                'descriptions' => $description,
                'showToChilds' => $showToChilds,
                'groupStatus'  => $groupStatus
            ];

            $result = $this->client->ChangeGroup($params);
            return $result->ChangeGroupResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * حذف گروه
     *
     * @param int $groupId شناسه گروه
     * @return bool
     */
    public function removeGroup($groupId)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'groupId'  => $groupId
            ];

            $result = $this->client->RemoveGroup($params);
            return $result->RemoveGroupResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * دریافت لیست مخاطبین
     *
     * @param int $groupId شناسه گروه (0 برای همه)
     * @param string $keyword کلمه کلیدی برای جستجو
     * @param int $from شروع
     * @param int $count تعداد
     * @return array
     */
    public function getContacts($groupId = 0, $keyword = '', $from = 0, $count = 100)
    {
        try {
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'groupId'  => $groupId,
                'keyword'  => $keyword,
                'from'     => $from,
                'count'    => $count
            ];

            $result = $this->client->GetContacts($params);
            return $result->GetContactsResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * اضافه کردن مخاطب جدید
     *
     * @param array $groupIds آرایه شناسه گروه‌ها
     * @param string $firstName نام
     * @param string $lastName نام خانوادگی
     * @param string $nickname نام مستعار
     * @param string $corporation شرکت
     * @param string $mobileNumber شماره موبایل
     * @param string $phoneNumber شماره تلفن
     * @param string $email ایمیل
     * @param string $address آدرس
     * @param string $birthDate تاریخ تولد
     * @param string $descriptions توضیحات
     * @return int شناسه مخاطب
     */
    public function addContact(
        $groupIds,
        $firstName = '',
        $lastName = '',
        $nickname = '',
        $corporation = '',
        $mobileNumber = '',
        $phoneNumber = '',
        $email = '',
        $address = '',
        $birthDate = '',
        $descriptions = ''
    ) {
        try {
            $params = [
                'username'     => $this->username,
                'password'     => $this->password,
                'groupIds'     => $groupIds,
                'firstname'    => $firstName,
                'lastname'     => $lastName,
                'nickname'     => $nickname,
                'corporation'  => $corporation,
                'mobilenumber' => $mobileNumber,
                'phonenumber'  => $phoneNumber,
                'email'        => $email,
                'address'      => $address,
                'birthdate'    => $birthDate,
                'descriptions' => $descriptions
            ];

            $result = $this->client->AddContact($params);
            return $result->AddContactResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * ویرایش مخاطب
     *
     * @param int $contactId شناسه مخاطب
     * @param string $firstName نام
     * @param string $lastName نام خانوادگی
     * @param string $nickname نام مستعار
     * @param string $corporation شرکت
     * @param string $mobileNumber شماره موبایل
     * @param string $phoneNumber شماره تلفن
     * @param string $email ایمیل
     * @param string $address آدرس
     * @param string $birthDate تاریخ تولد
     * @param string $descriptions توضیحات
     * @return bool
     */
    public function changeContact(
        $contactId,
        $firstName = '',
        $lastName = '',
        $nickname = '',
        $corporation = '',
        $mobileNumber = '',
        $phoneNumber = '',
        $email = '',
        $address = '',
        $birthDate = '',
        $descriptions = ''
    ) {
        try {
            $params = [
                'username'     => $this->username,
                'password'     => $this->password,
                'contactId'    => $contactId,
                'firstname'    => $firstName,
                'lastname'     => $lastName,
                'nickname'     => $nickname,
                'corporation'  => $corporation,
                'mobilenumber' => $mobileNumber,
                'phonenumber'  => $phoneNumber,
                'email'        => $email,
                'address'      => $address,
                'birthdate'    => $birthDate,
                'descriptions' => $descriptions
            ];

            $result = $this->client->ChangeContact($params);
            return $result->ChangeContactResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * حذف مخاطب با شماره موبایل
     *
     * @param string $mobileNumber شماره موبایل
     * @return bool
     */
    public function removeContact($mobileNumber)
    {
        try {
            $params = [
                'username'     => $this->username,
                'password'     => $this->password,
                'mobilenumber' => $mobileNumber
            ];

            $result = $this->client->RemoveContact($params);
            return $result->RemoveContactResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * حذف مخاطب با شناسه
     *
     * @param int $contactId شناسه مخاطب
     * @return bool
     */
    public function removeContactById($contactId)
    {
        try {
            $params = [
                'username'  => $this->username,
                'password'  => $this->password,
                'contactId' => $contactId
            ];

            $result = $this->client->RemoveContactByContactID($params);
            return $result->RemoveContactByContactIDResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }

    /**
     * بررسی وجود شماره در مخاطبین
     *
     * @param string $mobileNumber شماره موبایل
     * @return bool
     */
    public function checkMobileExist($mobileNumber)
    {
        try {
            $params = [
                'username'     => $this->username,
                'password'     => $this->password,
                'mobilenumber' => $mobileNumber
            ];

            $result = $this->client->CheckMobileExistInContact($params);
            return $result->CheckMobileExistInContactResult;

        } catch (SoapFault $e) {
            return $this->handleSoapFault($e);
        }
    }
}