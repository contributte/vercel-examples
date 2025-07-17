#!/bin/bash
set -exo pipefail

SESSION="vercel-examples"
PWD=$(pwd)

# Start a new session
tmux new-session -d -s $SESSION -c ${PWD}/php-8.3

# Split the window into multiple panes
tmux split-window -h -t $SESSION:0.0 -c ${PWD}/php-8.2
tmux split-window -v -t $SESSION:0.1 -c ${PWD}/php-8.1
tmux split-window -v -t $SESSION:0.2 -c ${PWD}/php-8.0
tmux split-window -h -t $SESSION:0.3 -c ${PWD}/php-7.4
tmux split-window -v -t $SESSION:0.4 -c ${PWD}/php-exec

# Tidy up the panes
tmux select-layout -t $SESSION:0 tiled

# Focus on the first pane
tmux select-pane -t $SESSION:0.0

# Attach to session
tmux attach -t $SESSION
