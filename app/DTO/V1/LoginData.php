<?

namespace App\DTO\V1;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class LoginData extends Data
{


    public function __construct(
        #[MapInputName('login')]
        public string $email,
        #[MapInputName('pass')]
        public string $password
    ) {}
}
