<?php
namespace Sirius\Html;

class Builder {

	protected $tagFactories = array(
		'button'      => 'Sirius\Html\Tag\Button',
		'checkbox'    => 'Sirius\Html\Tag\Checkbox',
		'div'         => 'Sirius\Html\Tag\Div',
		'file'        => 'Sirius\Html\Tag\File',
		'hidden'      => 'Sirius\Html\Tag\Hidden',
		'img'         => 'Sirius\Html\Tag\Img',
		'multiselect' => 'Sirius\Html\Tag\MultiSelect',
		'p'           => 'Sirius\Html\Tag\Paragraph',
		'paragraph'   => 'Sirius\Html\Tag\Paragraph',
		'password'    => 'Sirius\Html\Tag\Password',
		'radio'       => 'Sirius\Html\Tag\Radio',
		'select'      => 'Sirius\Html\Tag\Select',
		'text'        => 'Sirius\Html\Tag\Text',
		'textarea'    => 'Sirius\Html\Tag\Textarea'
	);

	/**
	 * Add an element factory (class or callback)
	 *
	 * @param string $name
	 * @param mixed $classOrCallback
	 *
	 * @return self
	 */
	public function registerTag( $name, $classOrCallback ) {
		$this->tagFactories[ $name ] = $classOrCallback;

		return $this;
	}

	/**
	 * Make an HTML tag with a specific tag name (div, p, section etc)
	 *
	 * @param string $tag
	 * @param mixed $content
	 * @param mixed $props
	 *
	 * @throws \InvalidArgumentException
	 * @return Tag
	 */
	public function make( $tag, $content = null, $props = null ) {
		if ( ! isset( $this->tagFactories[ $tag ] ) ) {
			return Tag::create( $tag, $content, $props, $this );
		}

		$constructor = $this->tagFactories[ $tag ];

		if ( is_callable( $constructor ) ) {
			/* @var $tag Tag */
			$tag = call_user_func( $constructor, $content, $props, $this );
		} elseif ( is_string( $constructor ) && class_exists( $constructor ) ) {
			/* @var $tag Tag */
			$tag = new $constructor( $content, $props, $this );
		}

		if ( ! $tag || ! $tag instanceof Tag ) {
			throw  new \InvalidArgumentException( sprintf( 'The constructor for the `%s` tag did not generate a Tag object',
				$tag ) );
		}

		return $tag;

	}

	/**
	 * Magic method for creating HTML tags
	 *
	 * @example $builder->h1(null, 'Heading 1');
	 *          $builder->article(['class' => 'post-body'], 'Article body');
	 *
	 * @param string $method
	 * @param array $args
	 *
	 * @return Tag
	 */
	public function __call( $method, $args ) {
		$method = preg_replace( '/([A-Z]+)/', '-\1', $method );
		$method = strtolower( $method );
		if ( ! isset( $args[0] ) ) {
			$args[0] = array();
		}
		if ( ! isset( $args[1] ) ) {
			$args[1] = null;
		}
		if ( ! isset( $args[2] ) ) {
			$args[2] = null;
		}

		return $this->make( $method, $args[0], $args[1], $args[2] );
	}
}
