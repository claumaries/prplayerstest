<?php

namespace App\Services;

use App\Enums\PrefixEnums;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserService implements UserServiceInterface
{
    /**
     * Pagination default length
     */
    public const DEFAULT_PER_PAGE = 10;

    /**
     * Constructor to bind model to a service.
     *
     * @param User    $model
     * @param Request $request
     * @param Rule    $rule
     * @param Hash    $hash
     */
    public function __construct(
        protected readonly User    $model,
        protected readonly Request $request,
        protected readonly Rule    $rule,
        protected readonly Hash    $hash,
    ) {}

    /**
     * Define the validation rules for the model.
     *
     * @param int|null $id
     *
     * @return array
     */
    public function rules(?int $id = null): array
    {
        // Validate password only for create when the $id is null
        $validatePassword = [];
        if(!$id) {
            $validatePassword['password'] = ['nullable', 'min:8', 'max:255', 'confirmed', Password::defaults()];
        }

        return array_merge(
            [
                'prefixname' => ['required', $this->rule::in(PrefixEnums::values())],
                'firstname'  => ['required', 'string', 'max:255'],
                'middlename' => ['nullable', 'string', 'max:255'],
                'lastname'   => ['required', 'string', 'max:255'],
                'suffixname' => ['nullable', 'string', 'max:255'],
                'username'   => ['required', 'string', 'max:255', 'unique:users,username,' . $id . ',id'],
                'photo'      => ['nullable', 'mimes:jpg,png,jpeg,gif,svg'],
                'email'      => ['required', 'email', 'max:255', 'unique:users,email,' . $id . ',id'],
            ],
            $validatePassword
        );
    }

    /**
     * Retrieve all resources and paginate.
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        return $this->model::paginate(self::DEFAULT_PER_PAGE);
    }

    /**
     * Create model resource.
     *
     * @param array $attributes
     *
     * @return Model
     */
    public function store(array $attributes): Model
    {
        return $this->model::create($attributes);
    }

    /**
     * Update model resource.
     *
     * @param integer $id
     * @param array   $attributes
     *
     * @return boolean
     */
    public function update(int $id, array $attributes): bool
    {
        $user = $this->find($id);

        if (!$user) {
            return false;
        }

        return $user->update($attributes);
    }

    /**
     * Retrieve model resource details.
     * Abort to 404 if not found.
     *
     * @param integer $id
     *
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->model::withTrashed()->with('details')->findOrFail($id);
    }

    /**
     * Soft delete model resource.
     *
     * @param integer|array $id
     *
     * @return void
     */
    public function destroy(int|array $id): void
    {
        if (is_array($id)) {
            $this->model::forIds('id', $id)?->delete();
        } else {
            $this->model->find($id)?->delete();
        }
    }

    /**
     * Permanently delete model resource.
     *
     * @param integer|array $id
     *
     * @return void
     */
    public function delete(int|array $id): void
    {
        if (is_array($id)) {
            $this->model->onlyTrashed()->forIds('id', $id)->forceDelete();
        } else {
            $this->model->onlyTrashed()->where('id', $id)->forceDelete();
        }
    }

    /**
     * Include only soft deleted records in the results.
     *
     * @return LengthAwarePaginator
     */
    public function listTrashed(): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()->paginate(self::DEFAULT_PER_PAGE);
    }

    /**
     * Restore model resource.
     *
     * @param integer|array $id
     *
     * @return void
     */
    public function restore(int|array $id): void
    {
        if (is_array($id)) {
            $this->model->onlyTrashed()->forIds('id', $id)->restore();
        } else {
            $this->model->onlyTrashed()->where('id', $id)->restore();
        }
    }

    /**
     * Generate random hash key.
     *
     * @param string $key
     *
     * @return string
     */
    public function hash(string $key): string
    {
        return $this->hash::make($key);
    }

    /**
     * Upload the given file.
     *
     * @param UploadedFile $file
     *
     * @return string|null
     */
    public function upload(UploadedFile $file): ?string
    {
        if ($file->isValid()) {
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('avatars', $filename, 'public');

            return $filename;
        }

        return null;
    }

    /**
     * Stores the user details
     *
     * @param int   $id
     * @param array $details
     *
     * @return void
     */
    public function storeDetails(int $id, array $details): void
    {
        $user = $this->find($id);

        $user->details()->saveMany($details);
    }
}
