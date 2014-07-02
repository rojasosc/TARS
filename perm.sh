#!/usr/bin/bash

flags=""
# directories for webserver
v_dir="$flags 0755"
# files for webserver
v_file="$flags 0644"
# this file
v_xfile="$flags 0700"

# primary app perms
chmod $v_file *
chmod $v_dir . css fonts images js plugins plugins/password_compat professor staff student
chmod $v_file css/* fonts/* images/* js/* professor/* staff/* student/* plugins/password_compat/*
# git version perms
chmod $v_file .git/*
chmod $v_dir .git/branches .git/hooks .git/info .git/logs .git/objects .git/refs
chmod $v_file .git/branches/* .git/hooks/* .git/info/*
chmod $v_file .git/logs/HEAD .git/logs/refs/heads/* .git/logs/refs/remotes/*/*
chmod $v_dir .git/logs/refs .git/logs/refs/heads .git/logs/refs/remotes .git/logs/refs/remotes/*
chmod $v_file .git/objects/*/*
chmod $v_dir .git/objects/*
chmod $v_file .git/refs/heads/* .git/refs/remotes/*/* .git/refs/tags/*
chmod $v_dir .git/refs/heads .git/refs/remotes .git/refs/remotes/* .git/refs/tags
chmod $v_xfile perm.sh

