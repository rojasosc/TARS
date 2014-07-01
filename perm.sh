#!/usr/bin/bash

flags="-f"
v_dir="$flags 0755"
v_file="$flags 0644"
v_xfile="$flags 0700"

# primary app perms
chmod $v_file * */* plugins/password_compat/*
chmod $v_dir . .git css fonts images js plugins plugins/password_compat professor staff student
# git version perms
chmod $v_file .git/* .git/branches/* hooks/* info/*
chmod $v_dir .git .git/branches .git/hooks .git/info .git/logs .git/objects .git/refs
chmod $v_file .git/logs/* .git/logs/refs/heads/* .git/logs/refs/remotes/*/*
chmod $v_dir .git/logs/refs .git/logs/refs/heads .git/logs/refs/remotes .git/logs/refs/remotes/*
chmod $v_file .git/objects/*/*
chmod $v_dir .git/objects/*
chmod $v_file .git/refs/heads/* .git/refs/remotes/*/* .git/refs/tags/*
chmod $v_dir .git/refs/heads .git/refs/remotes .git/refs/remotes/* .git/refs/tags
chmod $v_xfile perm.sh

