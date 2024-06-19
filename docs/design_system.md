# The Game
The Towers of Hanoi is a classic problem that is often used to teach recursion. The problem consists of three rods and a number of disks of different sizes which can slide onto any rod. The objective of the puzzle is to move the entire stack of disks from the first rod to the last rod, obeying the following rules:

1. Only one disk can be moved at a time.

2. Each move consists of taking the top disk from one of the stacks and placing it on top of another stack.

3. No disk may be placed on top of a smaller disk.

The game will track the number of moves the player makes, and the player can reset the game at any time.

# Design System
## Structural Components
- Header
    - The header contains the navigation bar, it is consistent across all pages.
- Score/move counter
    - The score/move counter is displayed at the top of the game area, it is plain text to make it easy to read.
- Game Area
    - The game area contains the pillars and the discs, it is the main focus of the game, it contains all elements of the game.
- Pillars
    - The pillars are the three rods that the discs can be placed on, they are the same height and width.
- Discs
    - The discs are the objects that can be moved between the pillars, they are different sizes and colours to make them easy to differentiate.
- Reset Button
    - The reset button is displayed at the top of the game area, it is a button that the player can click to reset the game, it uses an arrow to make the function of the button more obvious.

## Colour Palette
Used different colours to differentiate between the pillars and the discs. The background is a light grey to make the game area stand out.
The discs are are coloured differently to make it easier to differentiate between them.

# Fonts / Sizes
The font used is arial in size 12, this is a standard font and size that is easy to read. This is to create better readability for the player.



