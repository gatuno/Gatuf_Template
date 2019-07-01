<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Display the messages for the current user.
 */
class APP_CAMEL_CASE_Template_Tag_Messages extends Gatuf_Template_Tag {
	function start($user) {
		if (is_object($user) && !$user->isAnonymous() && get_class ($user) == Gatuf::config('gatuf_custom_user','Gatuf_User')) {
			$messages = $user->getAndDeleteMessages();
			if (count($messages) > 0) {
				echo '<div class="user-messages">'."\n";
				foreach ($messages as $m) {
					switch ($m['type']) {
						case 1:
							$clase = "class_1";
							break;
						case 2:
							$clase = "class_2";
							break;
						case 3:
							$clase = "class_3";
							break;
						case 4:
							$clase = "class_4";
							break;
						case 5:
							$clase = "class_5";
							break;
						case 6:
							$clase = "class_6";
							break;
					}
					echo '<input type="checkbox" id="m_'.$m['id'].'" class="m_close" autocomplete="off" />';
					echo '<div class="'.$clase.'">'.$m['message'];
					echo '<label for="m_'.$m['id'].'" class="user-messages-close">';
					echo '<span>x</span></label>';
					echo "</div>\n";
				}
				echo '</div>';
			}
		}
	}
}
