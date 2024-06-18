<?php

namespace App\Http\Controllers;

use App\Enums\PrefixEnums;
use App\Http\Requests\UserRequest;
use App\Services\UserServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * @param Inertia              $inertia
     * @param UserServiceInterface $userService
     */
    public function __construct(
        private readonly Inertia              $inertia,
        private readonly UserServiceInterface $userService,
    ) {}

    /**
     * Display the user's list
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->inertia::render('Users/Index', [
            'users' => $this->userService->list(),
        ]);
    }

    /**
     * Display the create user page
     *
     * @return Response
     */
    public function create(): Response
    {
        return $this->inertia::render('Users/Create', [
            'prefixes' => PrefixEnums::values(),
        ]);
    }

    /**
     * Create a new user
     *
     * @param UserRequest $request
     *
     * @return RedirectResponse
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $password       = $request->get('password');
        $redirectAction = $request->get('redirectRoute');

        // Has the password if exists
        $hashedPassword = null;
        if ($password) {
            $hashedPassword = $this->userService->hash($password);
        }

        // Prepare data to be stored
        $userData = $request->only([
            'prefixname',
            'firstname',
            'middlename',
            'lastname',
            'suffixname',
            'username',
            'email',
        ]);

        // Upload the file if exists
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $filename = $this->userService->upload($request->file('photo'));

            // Add photo to the array
            $userData['photo'] = $filename;
        }

        // Add password to the array
        $userData['password'] = $hashedPassword;

        // Store user data
        $this->userService->store($userData);

        // Redirect to the specified route
        return $redirectAction
            ? redirect()->route($redirectAction)
            : redirect()->back();
    }

    /**
     * Display the user's profile page
     * Abort to 404 if not found.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(int $id): Response
    {
        $user = $this->userService->find($id);

        if (!$user) {
            abort(404);
        }

        return $this->inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }

    /**
     * Display the user's update page
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(int $id): Response
    {
        $user = $this->userService->find($id);

        if (!$user) {
            abort(404);
        }

        return $this->inertia::render('Users/Edit', [
            'user'     => $user,
            'prefixes' => PrefixEnums::values(),
        ]);
    }

    /**
     * Update a user by id
     *
     * @param UserRequest $request
     * @param int         $id
     *
     * @return RedirectResponse
     */
    public function update(UserRequest $request, int $id): RedirectResponse
    {
        $redirectAction = $request->get('redirectRoute');

        $userData = $request->only([
            'prefixname',
            'firstname',
            'middlename',
            'lastname',
            'suffixname',
            'username',
            'email',
        ]);

        // Upload the file if exists
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $filename = $this->userService->upload($request->file('photo'));

            // Add photo to the array
            $userData['photo'] = $filename;
        }

        $this->userService->update($id, $userData);

        return $redirectAction === 'users.edit'
            ? redirect()->route('users.edit', $id)
            : redirect()->route($redirectAction);
    }

    /**
     * Soft delete the user
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = intval($request->get('userId'));

        $this->userService->destroy($userId);

        return redirect()->route('users.index');
    }

    /**
     * @return Response
     */
    public function trashed(): Response
    {
        return $this->inertia::render('Users/Trashed', [
            'users' => $this->userService->listTrashed(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function restore(Request $request): RedirectResponse
    {
        $userId = intval($request->get('userId'));

        $this->userService->restore($userId);

        return redirect()->route('users.trashed');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function forceDelete(Request $request): RedirectResponse
    {
        $userId = intval($request->get('userId'));

        $this->userService->delete($userId);

        return redirect()->route('users.trashed');
    }
}
