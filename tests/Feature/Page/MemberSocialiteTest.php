<?php

/*
 * This file is part of the Qsnh/meedu.
 *
 * (c) XiaoTeng <616896861@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Tests\Feature\Page;

use Tests\TestCase;
use App\Services\Member\Models\User;
use App\Services\Member\Models\Socialite;

class MemberSocialiteTest extends TestCase
{
    public function test_page()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user)->visit(route('member.socialite'))->seeStatusCode(200);
    }

    public function test_enabled_app()
    {
        config(['meedu.member.socialite.github.enabled' => 1]);
        config(['meedu.member.socialite.qq.enabled' => 0]);
        config(['meedu.member.socialite.weixinweb.enabled' => 0]);
        $user = factory(User::class)->create();
        $this->actingAs($user)
            ->visit(route('member.socialite'))
            ->seeStatusCode(200)
            ->dontSee('微信')
            ->see('Github');
    }

    public function test_cancel_button()
    {
        config(['meedu.member.socialite.github.enabled' => 1]);
        config(['meedu.member.socialite.qq.enabled' => 1]);
        config(['meedu.member.socialite.weixinweb.enabled' => 1]);
        $user = factory(User::class)->create();
        factory(Socialite::class)->create([
            'user_id' => $user->id,
            'app' => 'qq',
        ]);
        $this->actingAs($user)
            ->visit(route('member.socialite'))
            ->seeStatusCode(200)
            ->see('取消');
    }
}
