<?php

namespace OCA\FilesScripts\Interpreter\Functions\Nextcloud;

use OC\SystemTag\SystemTagManager;
use OC\SystemTag\SystemTagObjectMapper;
use OCA\FilesScripts\Interpreter\RegistrableFunction;
use OCP\Files\InvalidPathException;
use OCP\Files\NotFoundException;

/**
 * `tag_file(Node file, Tag tag): Bool`
 *
 * Adds a tag to a file. Returns whether the tag was added successfully.
 *
 * ```lua
 * local tags = tags_find({id= 42})
 * if (#tags == 1) then
 *   tag_file(get_input_files()[1], tags[1])
 * end
 * ```
 */
class Tag_File extends RegistrableFunction {
	use TagsSerializerTrait;

	private SystemTagManager $tagManager;
	private SystemTagObjectMapper $tagMapper;

	public function __construct(SystemTagManager $tagManager, SystemTagObjectMapper $tagMapper) {
		$this->tagManager = $tagManager;
		$this->tagMapper = $tagMapper;
	}

	public function run($file = [], $tagData = []): bool {
		$fileNode = $this->getFile($this->getPath($file));
		$tag = $this->deserializeTag($tagData, $this->tagManager);
		if (!$fileNode || !$tag) {
			return false;
		}

		try {
			$this->tagMapper->assignTags($fileNode->getId(), 'files', [$tag->getId()]);
		} catch (InvalidPathException|NotFoundException $e) {
			return false;
		}

		return true;
	}
}
