<?php

/*
 * This file is apart of the DiscordPHP project.
 *
 * Copyright (c) 2016-2020 David Cole <david.cole1340@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace Discord\WebSockets\Events;

use Discord\Parts\User\Member;
use Discord\WebSockets\Event;
use React\Promise\Deferred;

class GuildMemberRemove extends Event
{
    /**
     * {@inheritdoc}
     */
    public function handle(Deferred &$deferred, $data)
    {
        $member = $this->factory->create(Member::class, $data, true);

        if ($guild = $this->discord->guilds->get('id', $member->guild_id)) {
            $guild->members->pull($member->user->id);
            --$guild->member_count;

            $this->discord->guilds->push($guild);
        }

        $deferred->resolve($member);
    }
}
