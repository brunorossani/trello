<?php

namespace App\Enums;

enum TeamPermission: string
{
    case UpdateTeam = 'team:update';
    case DeleteTeam = 'team:delete';

    case AddMember = 'member:add';
    case UpdateMember = 'member:update';
    case RemoveMember = 'member:remove';

    case CreateInvitation = 'invitation:create';
    case CancelInvitation = 'invitation:cancel';

    case CreateBoard = 'board:create';
    case UpdateBoard = 'board:update';
    case DeleteBoard = 'board:delete';

    case CreateBoardList = 'board_list:create';
    case UpdateBoardList = 'board_list:update';
    case DeleteBoardList = 'board_list:delete';
}
