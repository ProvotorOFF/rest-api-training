<?

namespace App\Services;

use App\Http\Requests\Posts\StoreRequest;
use App\Models\Post;

class PostService {

    public function createWithImage(StoreRequest $request) {
        if ($request->file('thumbnail')) {
            $filePath = $request->file('thumbnail')->storePublicly('images');
            $request->merge(['thumbnail' => $filePath]);
        }
        return Post::create($request->validated());
    }

    public function all() {
        $user = auth()->user();
        if (!$user) return Post::whereStatus('published')->get();
        if ($user->can_view_drafts) return Post::all();
        return Post::whereStatus('published')->orWhere('user_id', $user->id)->get();
    }

}
