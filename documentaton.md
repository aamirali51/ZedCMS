Installation
Add the component via the Tiptap CLI:

npx @tiptap/cli@latest add floating-element

Components
<FloatingElement />
A versatile React component that creates floating UI elements positioned relative to text selections in Tiptap editors.

Usage
import * as React from 'react'
import { EditorContent, EditorContext, useEditor } from '@tiptap/react'

// --- Tiptap Core Extensions ---
import { StarterKit } from '@tiptap/starter-kit'

// --- Tiptap UI ---
import { FloatingElement } from '@/components/tiptap-ui-utils/floating-element'
import { MarkButton } from '@/components/tiptap-ui/mark-button'

// --- UI Primitives ---
import { ButtonGroup } from '@/components/tiptap-ui-primitive/button'
import { Toolbar } from '@/components/tiptap-ui-primitive/toolbar'

// --- Tiptap Node ---
import '@/components/tiptap-node/paragraph-node/paragraph-node.scss'

export const FloatingElementExample = () => {
  const editor = useEditor({
    immediatelyRender: false,
    content: `<h2>Floating Element Example</h2>
      <p>Try selecting some text in this editor. A simple formatting toolbar will appear above your selection. 
      The FloatingElement component positions UI elements relative to the text selection or cursor position. 
      It's commonly used for contextual toolbars, menus, and other elements that should appear near the current editing context.</p>`,
    extensions: [StarterKit],
  })

  return (
    <EditorContext.Provider value={{ editor }}>
      <EditorContent editor={editor} role="presentation" />

      <FloatingElement editor={editor}>
        <Toolbar variant="floating">
          <ButtonGroup orientation="horizontal">
            <MarkButton type="bold" />
            <MarkButton type="italic" />
          </ButtonGroup>
        </Toolbar>
      </FloatingElement>
    </EditorContext.Provider>
  )
}

Props
Name	Type	Default	Description
editor	Editor | null	undefined	The Tiptap editor instance to attach to
shouldShow	boolean	undefined	Controls whether the floating element should be visible
floatingOptions	Partial<UseFloatingOptions>	undefined	Additional options to pass to the floating UI
zIndex	number	50	Z-index for the floating element
onOpenChange	(open: boolean) => void	undefined	Callback fired when the visibility state changes
referenceElement	HTMLElement | null	undefined	Reference element to position the floating element relative to. If provided, this takes precedence over getBoundingClientRect
getBoundingClientRect	(editor: Editor) => DOMRect | null	getSelectionBoundingRect	Custom function to determine the position of the floating element. Only used if referenceElement is not provided
closeOnEscape	boolean	true	Whether to close the floating element when Escape key is pressed
children	React.ReactNode	undefined	Content to display inside the floating element
Advanced Usage Examples
Basic Floating Toolbar
import { shift, flip, offset } from '@floating-ui/react'
import { FloatingElement } from '@/components/tiptap-ui-utils/floating-element'

function FloatingToolbar({ editor }) {
  return (
    <FloatingElement
      editor={editor}
      floatingOptions={{
        placement: 'top',
        middleware: [shift(), flip(), offset(8)],
      }}
    >
      {/* Floating content here */}
    </FloatingElement>
  )
}

Custom Positioning with Mobile Support
import { FloatingElement } from '@/components/tiptap-ui-utils/floating-element'
import { useMobile } from '@/hooks/use-mobile'

function ResponsiveFloatingMenu({ editor, isMenuVisible }) {
  const isMobile = useMobile()

  const getCustomRect = (editor) => {
    // Custom positioning logic
    // Example: position relative to current cursor
    return editor.view.coordsAtPos(editor.state.selection.from)
  }

  return (
    <FloatingElement
      editor={editor}
      shouldShow={isMenuVisible}
      getBoundingClientRect={getCustomRect}
      {...(isMobile
        ? {
            style: {
              position: 'fixed',
              left: 0,
              right: 0,
              bottom: 0,
              margin: '.5rem',
              zIndex: 50,
            },
          }
        : {})}
    >
      {/* Floating content here */}
    </FloatingElement>
  )
}

Customize shouldShow Floating Menu
import { useState, useEffect } from 'react'
import { FloatingElement } from '@/components/tiptap-ui-utils/floating-element'
import { isSelectionValid } from '@/lib/tiptap-collab-utils'

function SelectionMenu({ editor }) {
  const [isVisible, setIsVisible] = useState(false)

  useEffect(() => {
    if (!editor) return

    const updateVisibility = () => {
      const hasSelection = !editor.state.selection.empty
      const isValidSelection = isSelectionValid(editor)
      setIsVisible(hasSelection && isValidSelection)
    }

    editor.on('selectionUpdate', updateVisibility)
    return () => editor.off('selectionUpdate', updateVisibility)
  }, [editor])

  return (
    <FloatingElement editor={editor} shouldShow={isVisible}>
      {/* Your floating content here */}
    </FloatingElement>
  )
}

Using Reference Element
Attach the floating element to a specific DOM element instead of the text selection:

import { useState } from 'react'
import { offset, flip, shift } from '@floating-ui/react'
import { FloatingElement } from '@/components/tiptap-ui-utils/floating-element'

function ButtonWithTooltip() {
  const [buttonRef, setButtonRef] = useState<HTMLElement | null>(null)
  const [showTooltip, setShowTooltip] = useState(false)

  return (
    <>
      <button
        ref={setButtonRef}
        onMouseEnter={() => setShowTooltip(true)}
        onMouseLeave={() => setShowTooltip(false)}
      >
        Hover me
      </button>

      <FloatingElement
        editor={editor}
        referenceElement={buttonRef}
        shouldShow={showTooltip}
        floatingOptions={{
          placement: 'top',
          middleware: [offset(8), flip(), shift()],
        }}
      >
        <div className="tooltip">Helpful tooltip content</div>
      </FloatingElement>
    </>
  )
}

Utilities
getSelectionBoundingRect(editor)
Gets the bounding rectangle of the current selection in the editor.

Parameters:

editor - The Tiptap editor instance
Returns: DOMRect | null - The bounding rectangle of the current selection

import { getSelectionBoundingRect } from '@/lib/tiptap-collab-utils'

const rect = getSelectionBoundingRect(editor)
console.log('Selection bounds:', rect)

isSelectionValid(editor, selection?, excludedNodeTypes?)
Checks if the current selection is valid for showing floating elements. Returns false for empty selections, code blocks, excluded node types, and table cells.

Parameters:

editor - The Tiptap editor instance
selection (optional) - The selection to validate. Defaults to editor.state.selection
excludedNodeTypes (optional) - Array of node type names to exclude. Defaults to ['imageUpload', 'horizontalRule']
Returns: boolean - true if the selection is valid for floating elements

import { isSelectionValid } from '@/lib/tiptap-collab-utils'

const shouldShow = isSelectionValid(editor)

// With custom excluded node types
const isValid = isSelectionValid(editor, undefined, ['image', 'video'])

isTextSelectionValid(editor)
Checks if the current text selection is valid for editing. Returns false for empty selections, code blocks, and node selections.

Parameters:

editor - The Tiptap editor instance
Returns: boolean - true if the text selection is valid

import { isTextSelectionValid } from '@/lib/tiptap-collab-utils'

const canEdit = isTextSelectionValid(editor)
if (canEdit) {
  // Show text editing toolbar
}

isElementWithinEditor(editor, element)
Checks if a DOM element is within the editor's DOM tree. Useful for determining click/focus events.

Parameters:

editor - The Tiptap editor instance
element - The DOM element to check
Returns: boolean - true if the element is within the editor

import { isElementWithinEditor } from '@/components/tiptap-ui-utils/floating-element'

const handleClick = (event: MouseEvent) => {
  if (isElementWithinEditor(editor, event.target as Node)) {
    console.log('Clicked inside editor')
  }
}

Previously
Utils components
Next up

Color Highlight Button
Available for free
A fully accessible and customizable color highlight button for Tiptap editors. Apply background colors to selected text using keyboard shortcuts or UI, with support for dynamic color sets, custom rendering, and accessibility.


Installation
Add the component via the Tiptap CLI:

npx @tiptap/cli@latest add color-highlight-button

Components
<ColorHighlightButton />
A ready-to-use React button for applying color highlights to selected text in a Tiptap editor.

Usage
import { EditorContent, EditorContext, useEditor } from '@tiptap/react'

// --- Tiptap Core Extensions ---
import { StarterKit } from '@tiptap/starter-kit'
import { Highlight } from '@tiptap/extension-highlight'

// --- Tiptap UI ---
import { ColorHighlightButton } from '@/components/tiptap-ui/color-highlight-button'

// --- UI Primitive ---
import { ButtonGroup } from '@/components/tiptap-ui-primitive/button'

// --- Node Styles ---
import '@/components/tiptap-node/code-block-node/code-block-node.scss'
import '@/components/tiptap-node/paragraph-node/paragraph-node.scss'

export default function MyEditor() {
  const editor = useEditor({
    immediatelyRender: false,
    extensions: [StarterKit, Highlight.configure({ multicolor: true })],
    content: `
      <h2>Color Highlight Button Demo</h2>
      <p>Welcome to the color highlight button! This demo showcases functionality.</p>
      <h3>How to Use:</h3>
      <p>1. <strong>Select any text</strong> you want to highlight</p>
      <p>2. <strong>Click a color button</strong> to apply that <mark data-color="oklch(88.5% 0.062 18.334)" style="background-color: oklch(88.5% 0.062 18.334); color: inherit">highlight</mark></p>
    `,
  })

  return (
    <EditorContext.Provider value={{ editor }}>
      <ButtonGroup orientation="horizontal">
        <ColorHighlightButton tooltip="Red" highlightColor="oklch(88.5% 0.062 18.334)" />
        <ColorHighlightButton
          editor={editor}
          tooltip="Orange"
          highlightColor="oklch(90.1% 0.076 70.697)"
          text="Highlight"
          hideWhenUnavailable={true}
          showShortcut={true}
          onApplied={({ color, label }) => console.log(`Applied ${label} highlight: ${color}`)}
        />
      </ButtonGroup>

      <EditorContent editor={editor} role="presentation" />
    </EditorContext.Provider>
  )
}

Props
Name	Type	Default	Description
editor	Editor | null	undefined	The Tiptap editor instance
highlightColor	string	required	The highlight color to apply (CSS color value)
text	string	undefined	Optional text label for the button
hideWhenUnavailable	boolean	false	Hides the button when highlight is not applicable
onApplied	({ color, label }) => void	undefined	Callback fired after applying a highlight
showShortcut	boolean	false	Shows a keyboard shortcut badge (if available)
Hooks
useColorHighlight(config)
A powerful hook to build your own custom color highlight button with full control over behavior and rendering.

Usage
import { useColorHighlight } from '@/components/tiptap-ui/color-highlight-button'
import { Badge } from '@/components/tiptap-ui-primitive/badge'
import { parseShortcutKeys } from '@/lib/tiptap-utils'

function MyColorHighlightButton() {
  const { isVisible, isActive, canColorHighlight, handleColorHighlight, label, shortcutKeys } =
    useColorHighlight({
      editor: myEditor,
      highlightColor: 'var(--tt-color-highlight-blue)',
      label: 'Blue Highlight',
      hideWhenUnavailable: true,
      onApplied: ({ color, label }) => console.log(`Applied: ${label}`),
    })

  if (!isVisible) return null

  return (
    <button
      onClick={handleColorHighlight}
      disabled={!canColorHighlight}
      aria-label={label}
      aria-pressed={isActive}
      style={{ backgroundColor: isActive ? highlightColor : 'transparent' }}
    >
      {label}
      {shortcutKeys && <Badge>{parseShortcutKeys({ shortcutKeys })}</Badge>}
    </button>
  )
}

Props
Name	Type	Default	Description
editor	Editor | null	undefined	The Tiptap editor instance
highlightColor	string	required	The highlight color to apply
label	string	optional	Accessible label for the button
hideWhenUnavailable	boolean	false	Hides the button if highlight cannot be applied
mode	"mark" | "node"	"mark"	Highlighting mode (mark or node background)
onApplied	({ color, label, mode }) => void	undefined	Callback fired after applying highlight
Return Values
Name	Type	Description
isVisible	boolean	Whether the button should be rendered
isActive	boolean	If the highlight is currently active
canColorHighlight	boolean	If the highlight can be applied
handleColorHighlight	() => boolean	Function to apply the color highlight
handleRemoveHighlight	() => boolean	Function to remove the highlight
label	string	Accessible label text for the button
shortcutKeys	string	Keyboard shortcut (Cmd/Ctrl + Shift + H)
Icon	React.FC	Icon component used (HighlighterIcon)
mode	"mark" | "node"	The highlighting mode being used
Utilities
canColorHighlight(editor, mode?)
Checks if color highlight can be applied in the current editor state.

Parameters
Parameter	Type	Default	Description
editor	Editor | null	Required	The Tiptap editor instance
mode	"mark" | "node"	"mark"	Highlighting mode (mark or node background)
Returns
boolean - Whether highlight can be applied

Usage
import { canColorHighlight } from '@/components/tiptap-ui/color-highlight-button'

const canApply = canColorHighlight(editor)
const canApplyNode = canColorHighlight(editor, 'node')

isColorHighlightActive(editor, highlightColor?, mode?)
Checks if a color highlight is currently active in the selection.

Parameters
Parameter	Type	Default	Description
editor	Editor | null	Required	The Tiptap editor instance
highlightColor	string	undefined	The specific color to check for
mode	"mark" | "node"	"mark"	Highlighting mode (mark or node background)
Returns
boolean - Whether the highlight is active

Usage
import { isColorHighlightActive } from '@/components/tiptap-ui/color-highlight-button'

const isActive = isColorHighlightActive(editor) // Any highlight active
const isYellowActive = isColorHighlightActive(editor, 'var(--tt-color-highlight-yellow)') // Specific color active
const isNodeHighlight = isColorHighlightActive(editor, 'var(--tt-color-highlight-blue)', 'node')

removeHighlight(editor, mode?)
Removes the current highlight from the selection.

Parameters
Parameter	Type	Default	Description
editor	Editor | null	Required	The Tiptap editor instance
mode	"mark" | "node"	"mark"	Highlighting mode (mark or node background)
Returns
boolean - Whether the highlight was successfully removed

Usage
import { removeHighlight } from '@/components/tiptap-ui/color-highlight-button'

const success = removeHighlight(editor)
const successNode = removeHighlight(editor, 'node')

pickHighlightColorsByValue(values)
Filters the default highlight colors by their values.

Parameters
Parameter	Type	Description
values	string[]	Array of color values to filter by
Returns
HighlightColor[] - Array of matching highlight color objects

Usage
import { pickHighlightColorsByValue } from '@/components/tiptap-ui/color-highlight-button'

const colors = pickHighlightColorsByValue([
  'var(--tt-color-highlight-yellow)',
  'var(--tt-color-highlight-blue)',
])
// Returns the full color objects with label, value, and border properties

Keyboard Shortcuts
The color highlight button supports the following keyboard shortcut:

Cmd/Ctrl + Shift + H: Apply color highlight
This shortcut is automatically registered when using the ColorHighlightButton or useColorHighlight() hook, and applies the configured highlight color to the current selection.

Requirements
Dependencies
@tiptap/react - Core Tiptap React integration
@tiptap/extension-highlight - Highlight extension for text highlighting
react-hotkeys-hook - Keyboard shortcut management
Referenced Components
use-tiptap-editor (hook)
button (primitive)
badge (primitive)
tiptap-utils (lib)
highlighter-icon (icon)

Code Block Button
Available for free
A fully accessible code block button for Tiptap editors. Easily toggle selected content into a <codeBlock> with keyboard shortcut support and flexible customization options.


Installation
Add the component via the Tiptap CLI:

npx @tiptap/cli@latest add code-block-button

Components
<CodeBlockButton />
A prebuilt React component that toggles code block formatting.

Usage
import { useEditor, EditorContent, EditorContext } from '@tiptap/react'
import { StarterKit } from '@tiptap/starter-kit'
import { CodeBlockButton } from '@/components/tiptap-ui/code-block-button'

export default function MyEditor() {
  const editor = useEditor({
    immediatelyRender: false,
    extensions: [StarterKit],
    content: `
        <pre><code>console.log('Hello, World!');</code></pre>
        `,
  })

  return (
    <EditorContext.Provider value={{ editor }}>
      <CodeBlockButton
        editor={editor}
        text="Code"
        hideWhenUnavailable={true}
        showShortcut={true}
        onToggled={() => console.log('Code block toggled!')}
      />

      <EditorContent editor={editor} role="presentation" />
    </EditorContext.Provider>
  )
}

Props
Name	Type	Default	Description
editor	Editor | null	undefined	The Tiptap editor instance
text	string	undefined	Optional text label for the button
hideWhenUnavailable	boolean	false	Hides the button when code block is not applicable
onToggled	() => void	undefined	Callback fired after a successful toggle
showShortcut	boolean	false	Shows a keyboard shortcut badge (if available)
Hooks
useCodeBlock()
A custom hook to build your own code block toggle button with full control over rendering and behavior.

Usage
import { useCodeBlock } from '@/components/tiptap-ui/code-block-button'
import { parseShortcutKeys } from '@/lib/tiptap-utils'
import { Badge } from '@/components/tiptap-ui-primitive/badge'

function MyCodeBlockButton() {
  const { isVisible, isActive, canToggle, handleToggle, label, shortcutKeys, Icon } = useCodeBlock({
    editor: myEditor,
    hideWhenUnavailable: true,
    onToggled: () => console.log('Code block toggled!'),
  })

  if (!isVisible) return null

  return (
    <button onClick={handleToggle} disabled={!canToggle} aria-label={label} aria-pressed={isActive}>
      <Icon />
      {label}
      {shortcutKeys && <Badge>{parseShortcutKeys({ shortcutKeys })}</Badge>}
    </button>
  )
}

Props
Name	Type	Default	Description
editor	Editor | null	undefined	The Tiptap editor instance
hideWhenUnavailable	boolean	false	Hides the button if code block cannot be applied
onToggled	() => void	undefined	Callback fired after toggling code block
Return Values
Name	Type	Description
isVisible	boolean	Whether the button should be rendered
isActive	boolean	If the code block is currently active
canToggle	boolean	If the code block toggle is currently allowed
handleToggle	() => boolean	Function to toggle code block formatting
label	string	Accessible label text for the button
shortcutKeys	string	Keyboard shortcut (Cmd/Ctrl + Alt + C)
Icon	React.FC	Icon component for the code block button
Utilities
canToggle(editor, turnInto?)
Checks if code block can be toggled in the current editor state.

Parameters:

editor: Editor | null - The Tiptap editor instance
turnInto: boolean - Whether to check for convertible node types (default: true)
Returns: boolean - Whether the code block can be toggled

import { canToggle } from '@/components/tiptap-ui/code-block-button'

const canToggleBlock = canToggle(editor) // Check if can toggle
const canTurnInto = canToggle(editor, true) // Explicit: check if selection can be turned into a code block
const canToggleDirect = canToggle(editor, false) // Check if can toggle directly

toggleCodeBlock(editor)
Programmatically toggles code block formatting for the current selection.

Parameters:

editor: Editor | null - The Tiptap editor instance
Returns: boolean - Whether the toggle was successful

import { toggleCodeBlock } from '@/components/tiptap-ui/code-block-button'

const success = toggleCodeBlock(editor)
if (success) {
  console.log('Code block toggled successfully!')
}

shouldShowButton(props)
Determines if the code block button should be shown based on editor state and configuration.

Parameters:

props: object
editor: Editor | null - The Tiptap editor instance
hideWhenUnavailable: boolean - Whether to hide when unavailable
Returns: boolean - Whether the button should be shown

import { shouldShowButton } from '@/components/tiptap-ui/code-block-button'

const shouldShow = shouldShowButton({
  editor,
  hideWhenUnavailable: true,
})

Keyboard Shortcuts
The code block button supports the following keyboard shortcut:

Cmd/Ctrl + Alt + C: Toggle code block formatting
The shortcut is automatically registered when using either the <CodeBlockButton /> component or the useCodeBlock() hook.

Requirements
Dependencies
@tiptap/react - Core Tiptap React integration
@tiptap/starter-kit - Basic Tiptap extensions including code block support
react-hotkeys-hook - Keyboard shortcut management
Referenced Components
use-tiptap-editor (hook)
button (primitive)
badge (primitive)
tiptap-utils (lib)
code-block-icon (icon)