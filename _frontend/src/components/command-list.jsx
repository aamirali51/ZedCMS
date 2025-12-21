import React, { useState, useEffect, forwardRef, useImperativeHandle } from 'react'
import { Heading1, Heading2, List, ListOrdered, Image, Code } from 'lucide-react'

export const CommandList = forwardRef((props, ref) => {
    const [selectedIndex, setSelectedIndex] = useState(0)

    // Allow the parent to control selection via keyboard
    useImperativeHandle(ref, () => ({
        onKeyDown: ({ event }) => {
            if (event.key === 'ArrowUp') {
                upHandler()
                return true
            }
            if (event.key === 'ArrowDown') {
                downHandler()
                return true
            }
            if (event.key === 'Enter') {
                enterHandler()
                return true
            }
            return false
        },
    }))

    const upHandler = () => {
        setSelectedIndex((selectedIndex + props.items.length - 1) % props.items.length)
    }

    const downHandler = () => {
        setSelectedIndex((selectedIndex + 1) % props.items.length)
    }

    const enterHandler = () => {
        selectItem(selectedIndex)
    }

    const selectItem = (index) => {
        const item = props.items[index]
        if (item) {
            props.command(item)
        }
    }

    useEffect(() => setSelectedIndex(0), [props.items])

    return (
        <div className="bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden min-w-[200px]">
            {props.items.length ? (
                props.items.map((item, index) => (
                    <button
                        key={index}
                        onClick={() => selectItem(index)}
                        className={`w-full flex items-center gap-3 px-4 py-2 text-left text-sm transition-colors ${index === selectedIndex
                                ? 'bg-indigo-50 text-indigo-700'
                                : 'text-gray-700 hover:bg-gray-50'
                            }`}
                    >
                        <span className="text-gray-400">{item.icon}</span>
                        <span>{item.title}</span>
                    </button>
                ))
            ) : (
                <div className="px-4 py-2 text-sm text-gray-500">No results</div>
            )}
        </div>
    )
})

CommandList.displayName = 'CommandList'
