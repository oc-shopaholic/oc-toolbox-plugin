<?php namespace Lovata\Toolbox\Classes\Api\Mutation;

use GraphQL\Type\Definition\ResolveInfo;
use Lovata\Toolbox\Classes\Api\Type\Custom\Type as CustomType;
use Lovata\Toolbox\Classes\Api\Response\ApiDataResponse;
use Lovata\Toolbox\Classes\Api\Type\AbstractApiType;

use Illuminate\Validation\ValidationException;
use GraphQL\Type\Definition\Type;
use Lang;
use DB;

/**
 * Class AbstractMutationType
 * @package Lovata\Toolbox\Classes\Api\Mutation
 * @author Andrey Kharanenka, a.khoronenko@lovata.com, LOVATA Group
 */
abstract class AbstractMutationType extends AbstractApiType
{
    /** @var array */
    protected $arArgumentList = [];

    /** @var ResolveInfo */
    protected $obResolveInfo;

    /** @var bool Status of result (true|false) */
    protected $bStatus = false;

    /** @var array */
    protected $arResultData = [];

    /** @var October\Rain\Database\Model */
    protected $obModel;

    /** @var string Error message */
    protected $sErrorMessage = null;

    /** @var int Error code */
    protected $iErrorCode = null;

    /**
     * Mutation body
     * @return bool
     */
    abstract protected function mutation(): bool;

    /**
     * Get type fields
     * @return array
     */
    protected function getFieldList(): array
    {
        $arFieldList = [
            'status'  => Type::boolean(),
            'data'    => CustomType::array(),
            'message' => Type::string(),
            'code'    => Type::int(),
        ];

        return $arFieldList;
    }

    /**
     * Get resolve method for type
     * @return callable|null
     */
    protected function getResolveMethod(): ?callable
    {
        return function ($obValue, $arArgumentList, $sContext, $obResolveInfo) {
            $this->arArgumentList = $arArgumentList;
            $this->obResolveInfo = $obResolveInfo;

            $this->initArgumentList();

            //Check client access
            if (!$this->checkAccess($obResolveInfo->path, $this->obModel, $arArgumentList)) {
                return null;
            }

            $this->runMutationLogic();

            return $this->result();
        };
    }

    /**
     * Get result array
     * @return array
     */
    protected function result(): array
    {
        $arResult = [
            'status'  => $this->bStatus,
            'data'    => $this->arResultData,
            'message' => $this->sErrorMessage,
            'code'    => $this->iErrorCode,
        ];

        if (empty($this->obModel)) {
            return $arResult;
        }

        $arModelData = $this->obModel->toArray();

        return array_merge($arResult, $arModelData);
    }

    /**
     * Init arguments from request
     */
    protected function initArgumentList()
    {
        // Save mutation variables from argument list
    }

    /**
     * Method runs before mutation
     * @return bool
     */
    protected function beforeMutation(): bool
    {
        // Put logic before mutation here
        return true;
    }

    /**
     * Run mutation main logic
     * @throws \Throwable
     */
    protected function runMutationLogic()
    {
        if (!$this->beforeMutation()) {
            return;
        }

        $this->beginTransaction();

        try {
            if (!$this->mutation()) {
                $this->rollbackTransaction();

                return;
            }
        } catch (\Exception $obException) {
            $this->rollbackTransaction();
            $this->processValidationError($obException);

            return;
        }

        $this->commitTransaction();

        $this->afterMutation();
    }

    /**
     * Method runs after mutation
     */
    protected function afterMutation()
    {
        // Put logic after mutation here
        $this->bStatus = true;
    }

    /**
     * Begin transaction for all connections
     * @throws \Throwable
     */
    protected function beginTransaction()
    {
        DB::beginTransaction();
    }

    /**
     * Rollback transaction for all connections
     * @throws \Throwable
     */
    protected function rollbackTransaction()
    {
        DB::rollBack();
    }

    /**
     * Commit transaction for all connections
     * @throws \Throwable
     */
    protected function commitTransaction()
    {
        DB::commit();
    }

    /**
     * Set error message in response
     * @param int         $iCode
     * @param string|null $sMessage
     * @param array       $arReplace
     * @return bool
     */
    protected function setErrorMessage($iCode, $sMessage = null, $arReplace = []): bool
    {
        $this->bStatus = false;
        $this->iErrorCode = $iCode;
        if (empty($sMessage)) {
            $this->sErrorMessage = Lang::get('lovata.toolbox::lang.message.'.$iCode, $arReplace);
        } else {
            $this->sErrorMessage = Lang::get($sMessage, $arReplace);
        }

        return false;
    }

    /**
     * @param int    $iElementID
     * @param array  $arModelData
     * @param string $sModelClass
     * @return object|null
     */
    protected function createOrUpdateModel($iElementID, $arModelData, $sModelClass)
    {
        if (empty($arModelData) || empty($sModelClass)) {
            return null;
        }

        if (!empty($iElementID)) {
            $obModel = $sModelClass::find($iElementID);
            if (empty($obModel)) {
                $this->setErrorMessage(ApiDataResponse::CODE_NOT_FOUND);

                return $obModel;
            }
        } else {
            $obModel = null;
        }

        try {
            if (!empty($obModel)) {
                $obModel->update($arModelData);
            } else {
                $obModel = $sModelClass::create($arModelData);
            }
        } catch (ValidationException $obException) {
            $this->processValidationError($obException);

            return null;
        }

        return $obModel;
    }

    /**
     * Process validation error and save error message
     * @param \Exception $obException
     */
    protected function processValidationError($obException)
    {
        $this->bStatus = false;
        if (!empty($obException->validator)) {
            $obValidator = $obException->validator;
            $this->sErrorMessage = $obValidator->getMessageBag()->first();
            $this->iErrorCode = ApiDataResponse::CODE_NOT_CORRECT_REQUEST;
        } else {
            $this->sErrorMessage = $obException->getMessage();
            $this->iErrorCode = ApiDataResponse::CODE_ERROR;
        }
    }
}
